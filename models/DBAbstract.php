<?php
class DBAbstract
{
	/** @var mysqli */
	private $db;

	function __construct()
	{
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$this->db->set_charset('utf8mb4');
	}

	/**
	 * Ejecuta SQL simple. Autodetecta tipo: SELECT / INSERT / UPDATE / DELETE / CALL
	 * Para CALL, consume todos los result sets y devuelve el primero (si existe).
	 * Usá callSP() si necesitás OUT params o múltiples result sets.
	 */
	public function query(string $sql)
	{
		$type = $this->detectType($sql);

		$res = $this->db->query($sql);

		switch ($type) {
			case 'SELECT':
				return $res->fetch_all(MYSQLI_ASSOC);

			case 'INSERT':
				return $this->db->insert_id;

			case 'UPDATE':
			case 'DELETE':
				return $this->db->affected_rows;

			case 'CALL':
				// MariaDB puede devolver varios result sets
				$all = [];
				if ($res instanceof mysqli_result) {
					$all[] = $res->fetch_all(MYSQLI_ASSOC);
					$res->free();
				}
				while ($this->db->more_results()) {
					$this->db->next_result();
					if ($extra = $this->db->store_result()) {
						$all[] = $extra->fetch_all(MYSQLI_ASSOC);
						$extra->free();
					}
				}
				// Mantener compat con tu versión: devolver el primer set si existe
				return $all[0] ?? [];
		}

		return null;
	}

	/**
	 * Llamar stored procedures con parámetros IN (preparado) y leer OUT variables.
	 * $sql debe incluir las @vars OUT ya colocadas en el CALL.
	 * Ej.: callSP("CALL sp_crear_apunte(?,?,?,?,?,?,?,?,?,?, @apunte_id)", [...], ["@apunte_id"]);
	 */
	public function callSP(string $sql, array $inParams = [], array $outVars = []): array
	{
		// Preparado para IN params
		$stmt = $this->db->prepare($sql);
		if (!empty($inParams)) {
			$types = $this->inferTypes($inParams);
			$stmt->bind_param($types, ...$inParams);
		}
		$stmt->execute();

		// Primer result set (si lo hay)
		$resultSets = [];
		if ($res = $stmt->get_result()) {
			$resultSets[] = $res->fetch_all(MYSQLI_ASSOC);
			$res->free();
		}
		$stmt->close();

		// Consumir result sets adicionales del CALL
		while ($this->db->more_results()) {
			$this->db->next_result();
			if ($extra = $this->db->store_result()) {
				$resultSets[] = $extra->fetch_all(MYSQLI_ASSOC);
				$extra->free();
			}
		}

		// Leer OUT vars si se pidieron
		$out = [];
		if (!empty($outVars)) {
			$select = "SELECT " . implode(", ", array_map(function ($v) {
				return "$v AS `$v`";
			}, $outVars));
			$row = $this->db->query($select)->fetch_assoc();
			foreach ($outVars as $v) $out[$v] = isset($row[$v]) ? $row[$v] : null;
		}

		return ['result_sets' => $resultSets, 'out' => $out];
	}

	public function begin()
	{
		$this->db->begin_transaction();
	}
	public function commit()
	{
		$this->db->commit();
	}
	public function rollback()
	{
		$this->db->rollback();
	}

	/* ===== Helpers ===== */

	private function detectType(string $sql): string
	{
		if (preg_match('/^\s*(SELECT|INSERT|UPDATE|DELETE|CALL)\b/i', $sql, $m)) {
			return strtoupper($m[1]);
		}
		return 'OTHER';
	}

	private function inferTypes(array $params): string
	{
		// i (int), d (double), s (string), b (blob)
		$types = '';
		foreach ($params as $p) {
			if (is_int($p))         $types .= 'i';
			elseif (is_float($p))   $types .= 'd';
			elseif (is_null($p))    $types .= 's'; // pasamos NULL como string; MariaDB lo castea. Alternativa: usar NULL explícito en SQL.
			elseif (is_string($p))  $types .= 's';
			else                    $types .= 's';
		}
		return $types;
	}
}
