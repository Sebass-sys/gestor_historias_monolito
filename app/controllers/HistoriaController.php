<?php
class HistoriaController extends Controller
{
    private Historia $modelo;

    public function __construct()
    {
        $this->modelo = new Historia();
    }

    public function index(): void
    {
        $sprintId = (int)($_GET['sprint'] ?? 0);
        $estado   = (string)($_GET['estado'] ?? '');

        $this->render('historias/index', [
            'titulo'    => 'Historias de usuario',
            'historias' => $this->modelo->listar($sprintId, $estado),
            'sprints'   => (new Sprint())->todos(),
            'sprintId'  => $sprintId,
            'estado'    => $estado,
        ]);
    }

    public function nuevo(): void
    {
        $this->formulario([
            'titulo' => '', 'descripcion' => '', 'responsable' => '', 'prioridad' => 'media',
            'estado' => 'nueva', 'puntos' => 3, 'fecha_creacion' => date('Y-m-d'),
            'sprint_id' => (string)(int)($_GET['sprint'] ?? 0),
        ], [], 'guardar', 'Nueva historia');
    }

    public function guardar(): void
    {
        $datos   = $this->_leer();
        $errores = $this->_validar($datos);
        if ($errores) {
            $this->formulario($datos, $errores, 'guardar', 'Nueva historia');
            return;
        }
        $this->modelo->crear($datos);
        $this->flash('ok', 'Historia registrada correctamente.');
        $this->redirect('historia');
    }

    public function editar(): void
    {
        $h = $this->modelo->buscar((int)($_GET['id'] ?? 0));
        if (!$h) {
            $this->flash('error', 'La historia no existe.');
            $this->redirect('historia');
        }
        $this->formulario($h, [], 'actualizar', 'Editar historia');
    }

    public function actualizar(): void
    {
        $id      = (int)$this->post('id');
        $datos   = $this->_leer();
        $errores = $this->_validar($datos);

        if (!$this->modelo->buscar($id)) {
            $this->flash('error', 'La historia no existe.');
            $this->redirect('historia');
        }
        if ($errores) {
            $datos['id'] = $id;
            $this->formulario($datos, $errores, 'actualizar', 'Editar historia');
            return;
        }
        $this->modelo->actualizar($id, $datos);
        $this->flash('ok', 'Historia actualizada correctamente.');
        $this->redirect('historia');
    }

    public function avanzar(): void
    {
        try {
            $this->modelo->avanzar((int)$this->post('id'));
            $this->flash('ok', 'Estado de la historia actualizado.');
        } catch (DomainException $e) {
            $this->flash('error', $e->getMessage());
        }
        $this->redirect('historia');
    }

    public function eliminar(): void
    {
        $this->modelo->eliminar((int)$this->post('id'));
        $this->flash('ok', 'Historia eliminada.');
        $this->redirect('historia');
    }

    private function formulario(array $historia, array $errores, string $accion, string $titulo): void
    {
        $this->render('historias/form', [
            'titulo'   => $titulo,
            'historia' => $historia,
            'errores'  => $errores,
            'accion'   => $accion,
            'sprints'  => (new Sprint())->todos(),
        ]);
    }

    private function _leer(): array
    {
        return [
            'titulo'         => $this->post('titulo'),
            'descripcion'    => $this->post('descripcion'),
            'responsable'    => $this->post('responsable'),
            'prioridad'      => $this->post('prioridad'),
            'estado'         => $this->post('estado'),
            'puntos'         => $this->post('puntos'),
            'fecha_creacion' => $this->post('fecha_creacion'),
            'sprint_id'      => $this->post('sprint_id'),
        ];
    }

    private function _validar(array $d): array
    {
        $e = [];
        if (mb_strlen($d['titulo']) < 5)       { $e['titulo'] = 'El título debe tener al menos 5 caracteres.'; }
        if (mb_strlen($d['descripcion']) < 10) { $e['descripcion'] = 'Describe la historia (mínimo 10 caracteres).'; }
        if (mb_strlen($d['responsable']) < 2)  { $e['responsable'] = 'Indica el responsable.'; }
        if (!in_array($d['prioridad'], Historia::PRIORIDADES, true)) { $e['prioridad'] = 'Selecciona una prioridad válida.'; }
        if (!in_array($d['estado'], Historia::ESTADOS, true))        { $e['estado'] = 'Selecciona un estado válido.'; }
        if (!in_array((int)$d['puntos'], Historia::PUNTOS, true))    { $e['puntos'] = 'Selecciona los puntos de la historia.'; }

        $f = DateTime::createFromFormat('Y-m-d', $d['fecha_creacion']);
        if (!$f || $f->format('Y-m-d') !== $d['fecha_creacion']) {
            $e['fecha_creacion'] = 'Fecha inválida.';
        }
        if ((new Sprint())->buscar((int)$d['sprint_id']) === null) {
            $e['sprint_id'] = 'Selecciona un sprint.';
        }
        return $e;
    }
}
