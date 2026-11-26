<?php
/**
 * Modelo Sprint: acceso a datos de los sprints y sus métricas de avance.
 */
class Sprint extends Model
{
    /** Sprints con sus métricas (historias, puntos y puntos finalizados). */
    public function conMetricas(): array
    {
        $sql = "SELECT s.*,
                       COUNT(h.id) AS historias,
                       COALESCE(SUM(h.puntos), 0) AS puntos,
                       COALESCE(SUM(CASE WHEN h.estado = 'finalizada' THEN h.puntos ELSE 0 END), 0) AS puntos_finalizados
                FROM sprints s
                LEFT JOIN historias h ON h.sprint_id = s.id
                GROUP BY s.id, s.nombre, s.objetivo, s.fecha_inicio, s.fecha_fin
                ORDER BY s.fecha_inicio DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function todos(): array
    {
        return $this->db->query('SELECT * FROM sprints ORDER BY fecha_inicio DESC')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM sprints WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function crear(array $d): void
    {
        $st = $this->db->prepare('INSERT INTO sprints (nombre, objetivo, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)');
        $st->execute([$d['nombre'], $d['objetivo'], $d['fecha_inicio'], $d['fecha_fin']]);
    }

    public function actualizar(int $id, array $d): void
    {
        $st = $this->db->prepare('UPDATE sprints SET nombre=?, objetivo=?, fecha_inicio=?, fecha_fin=? WHERE id=?');
        $st->execute([$d['nombre'], $d['objetivo'], $d['fecha_inicio'], $d['fecha_fin'], $id]);
    }

    /** Elimina el sprint; sus historias se borran en cascada (llave foránea). */
    public function eliminar(int $id): void
    {
        $st = $this->db->prepare('DELETE FROM sprints WHERE id = ?');
        $st->execute([$id]);
    }

    public function total(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM sprints')->fetchColumn();
    }
}
