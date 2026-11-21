<?php
/**
 * Modelo Historia: historias de usuario y sus reglas de negocio
 * (flujo de estados y fecha de finalización automática).
 */
class Historia extends Model
{
    public const ESTADOS     = ['nueva', 'activa', 'finalizada', 'impedimento'];
    public const PRIORIDADES = ['alta', 'media', 'baja'];
    public const PUNTOS      = [1, 2, 3, 5, 8, 13, 21]; // serie de Fibonacci usada en Scrum

    /** Siguiente estado al pulsar el botón "avanzar". */
    public const SIGUIENTE = ['nueva' => 'activa', 'activa' => 'finalizada', 'impedimento' => 'activa'];

    private const SELECT_BASE =
        'SELECT h.*, s.nombre AS sprint FROM historias h JOIN sprints s ON s.id = h.sprint_id';

    public function listar(int $sprintId = 0, string $estado = ''): array
    {
        $sql = self::SELECT_BASE . ' WHERE 1 = 1';
        $par = [];
        if ($sprintId > 0) {
            $sql .= ' AND h.sprint_id = ?';
            $par[] = $sprintId;
        }
        if (in_array($estado, self::ESTADOS, true)) {
            $sql .= ' AND h.estado = ?';
            $par[] = $estado;
        }
        $st = $this->db->prepare($sql . ' ORDER BY h.id DESC');
        $st->execute($par);
        return $st->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->db->prepare(self::SELECT_BASE . ' WHERE h.id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    /** Regla: una historia finalizada guarda su fecha de cierre; las demás no tienen. */
    private function fechaFin(string $estado, ?string $actual): ?string
    {
        return $estado === 'finalizada' ? ($actual ?: date('Y-m-d')) : null;
    }

    public function crear(array $d): void
    {
        $st = $this->db->prepare(
            'INSERT INTO historias (titulo, descripcion, responsable, prioridad, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $st->execute([
            $d['titulo'], $d['descripcion'], $d['responsable'], $d['prioridad'], $d['estado'],
            (int)$d['puntos'], $d['fecha_creacion'], $this->fechaFin($d['estado'], null), (int)$d['sprint_id'],
        ]);
    }

    public function actualizar(int $id, array $d): void
    {
        $actual = $this->buscar($id);
        $st = $this->db->prepare(
            'UPDATE historias SET titulo=?, descripcion=?, responsable=?, prioridad=?, estado=?, puntos=?,
                    fecha_creacion=?, fecha_finalizacion=?, sprint_id=? WHERE id=?'
        );
        $st->execute([
            $d['titulo'], $d['descripcion'], $d['responsable'], $d['prioridad'], $d['estado'],
            (int)$d['puntos'], $d['fecha_creacion'], $this->fechaFin($d['estado'], $actual['fecha_finalizacion'] ?? null),
            (int)$d['sprint_id'], $id,
        ]);
    }

    /** Mueve la historia al siguiente estado del flujo. */
    public function avanzar(int $id): void
    {
        $h = $this->buscar($id);
        if (!$h || !isset(self::SIGUIENTE[$h['estado']])) {
            throw new DomainException('La historia no existe o ya está finalizada.');
        }
        $nuevo = self::SIGUIENTE[$h['estado']];
        $st = $this->db->prepare('UPDATE historias SET estado = ?, fecha_finalizacion = ? WHERE id = ?');
        $st->execute([$nuevo, $this->fechaFin($nuevo, $h['fecha_finalizacion']), $id]);
    }

    public function eliminar(int $id): void
    {
        $st = $this->db->prepare('DELETE FROM historias WHERE id = ?');
        $st->execute([$id]);
    }

    public function totales(): array
    {
        $f = $this->db->query(
            "SELECT COUNT(*) AS historias, COALESCE(SUM(puntos), 0) AS puntos,
                    COALESCE(SUM(CASE WHEN estado = 'finalizada' THEN puntos ELSE 0 END), 0) AS puntos_finalizados,
                    COALESCE(SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END), 0) AS impedimentos
             FROM historias"
        )->fetch();
        return array_map('intval', $f);
    }
}
