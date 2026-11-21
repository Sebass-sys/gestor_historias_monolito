# ScrumBoard UB — Gestor de historias de usuario y sprints (monolito)

Aplicación web **monolítica** (front end + back end en un solo proyecto y un solo despliegue) hecha en **PHP 8 + MySQL** con patrón **MVC**, HTML, CSS y JavaScript puros.
Materia: Programación Avanzada · Universidad de Boyacá.

**Tema:** gestionar el trabajo de un equipo con metodología ágil (Scrum): se planifican **sprints**, se registran **historias de usuario** dentro de cada sprint y se consultan **informes** de avance.

## Instalación en XAMPP

1. Copia la carpeta `gestor_historias_monolito` dentro de `C:\xampp\htdocs\`.
2. Inicia **Apache** y **MySQL** desde XAMPP.
3. En `http://localhost/phpmyadmin` abre la pestaña **SQL** (sin elegir ninguna base), pega el contenido de `database.sql` y pulsa **Continuar**.
4. Si tu MySQL tiene contraseña, cámbiala en `config/config.php`.
5. Entra a `http://localhost/gestor_historias_monolito/`.

> Requiere PHP 8.0 o superior.

## Funcionalidades

| Módulo | Qué hace |
|---|---|
| Panel | Totales, avance global por puntos, avance de cada sprint e historias con impedimento |
| Sprints | CRUD con nombre, objetivo y fechas; muestra historias, puntos y % de avance |
| Historias | CRUD con responsable, prioridad, puntos (Fibonacci) y estado; filtros por sprint y estado; botón para avanzar de estado |
| Informes | Resumen por sprint (o general), historias por estado y carga por responsable |

**Reglas de negocio**
- Estados: `nueva → activa → finalizada`; una historia en `impedimento` se reanuda a `activa`.
- Al finalizar una historia se guarda sola su fecha de finalización; si vuelve a otro estado, se borra.
- Los puntos solo pueden ser 1, 2, 3, 5, 8, 13 o 21.
- Todas las historias pertenecen a un sprint; al eliminar un sprint se eliminan sus historias (llave foránea en cascada).
- Avance = puntos finalizados ÷ puntos totales.

## Estructura MVC

```
gestor_historias_monolito/
├── index.php              Front Controller (único punto de entrada y router)
├── config/config.php      Configuración de la base de datos
├── core/                  Database (Singleton PDO), Model y Controller base
├── app/
│   ├── models/            Sprint, Historia, Informe  → datos y reglas de negocio
│   ├── controllers/       Home, Sprint, Historia, Informe → reciben, validan y eligen vista
│   └── views/             HTML por módulo + layout
├── public/css, public/js  Estilos y comportamiento en el navegador
└── database.sql           Script de la base de datos (con datos de ejemplo)
```

Por qué es un monolito: interfaz, lógica de negocio y datos se construyen y despliegan **juntos** como una sola aplicación, con una sola base de datos compartida por todos los módulos.
