<?php
class SprintController extends Controller
{
    private Sprint $modelo;

    public function __construct()
    {
        $this->modelo = new Sprint();
    }

    public function index(): void
    {
        $this->render('sprints/index', [
            'titulo'  => 'Sprints',
            'sprints' => $this->modelo->conMetricas(),
        ]);
    }

    public function nuevo(): void
    {
        $this->formulario([
            'nombre' => '', 'objetivo' => '',
            'fecha_inicio' => date('Y-m-d'), 'fecha_fin' => date('Y-m-d', strtotime('+14 days')),
        ], [], 'guardar', 'Nuevo sprint');
    }

    public function guardar(): void
    {
        $datos   = $this->_leer();
        $errores = $this->_validar($datos);
        if ($errores) {
            $this->formulario($datos, $errores, 'guardar', 'Nuevo sprint');
            return;
        }
        $this->modelo->crear($datos);
        $this->flash('ok', 'Sprint creado correctamente.');
        $this->redirect('sprint');
    }

    public function editar(): void
    {
        $s = $this->modelo->buscar((int)($_GET['id'] ?? 0));
        if (!$s) {
            $this->flash('error', 'El sprint no existe.');
            $this->redirect('sprint');
        }
        $this->formulario($s, [], 'actualizar', 'Editar sprint');
    }

    public function actualizar(): void
    {
        $id      = (int)$this->post('id');
        $datos   = $this->_leer();
        $errores = $this->_validar($datos);

        if (!$this->modelo->buscar($id)) {
            $this->flash('error', 'El sprint no existe.');
            $this->redirect('sprint');
        }
        if ($errores) {
            $datos['id'] = $id;
            $this->formulario($datos, $errores, 'actualizar', 'Editar sprint');
            return;
        }
        $this->modelo->actualizar($id, $datos);
        $this->flash('ok', 'Sprint actualizado correctamente.');
        $this->redirect('sprint');
    }

    public function eliminar(): void
    {
        $this->modelo->eliminar((int)$this->post('id'));
        $this->flash('ok', 'Sprint eliminado junto con sus historias.');
        $this->redirect('sprint');
    }

    private function formulario(array $sprint, array $errores, string $accion, string $titulo): void
    {
        $this->render('sprints/form', compact('sprint', 'errores', 'accion', 'titulo'));
    }

    private function _leer(): array
    {
        return [
            'nombre'       => $this->post('nombre'),
            'objetivo'     => $this->post('objetivo'),
            'fecha_inicio' => $this->post('fecha_inicio'),
            'fecha_fin'    => $this->post('fecha_fin'),
        ];
    }

    private function _validar(array $d): array
    {
        $e = [];
        if (mb_strlen($d['nombre']) < 3) {
            $e['nombre'] = 'El nombre debe tener al menos 3 caracteres.';
        }
        if (mb_strlen($d['objetivo']) > 255) {
            $e['objetivo'] = 'El objetivo no puede superar 255 caracteres.';
        }
        $ini = DateTime::createFromFormat('Y-m-d', $d['fecha_inicio']);
        $fin = DateTime::createFromFormat('Y-m-d', $d['fecha_fin']);
        if (!$ini || $ini->format('Y-m-d') !== $d['fecha_inicio']) {
            $e['fecha_inicio'] = 'Fecha de inicio inválida.';
        }
        if (!$fin || $fin->format('Y-m-d') !== $d['fecha_fin']) {
            $e['fecha_fin'] = 'Fecha de fin inválida.';
        } elseif (!isset($e['fecha_inicio']) && $d['fecha_fin'] < $d['fecha_inicio']) {
            $e['fecha_fin'] = 'La fecha de fin no puede ser anterior al inicio.';
        }
        return $e;
    }
}
