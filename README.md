# jquery-datatables
Plantilla que muestra diversas formas de uso de la librería jquery-datatables. El backend usa php con el framework laravel 5.6

#### Lista de ejemplos:
- Datatable Backend - Básico, con eliminación de registros;
- Datatable Backend - con procesamiento en el servidor, con filtros, orden por algunas columnas
 y eliminación de registros.

#### Instalación:
- Una vez descargado el repositorio no se olvide de copiar el archivo **.env.example**
 y renombrarlo como **.env**, luego ejecutar los siguientes comandos:
```console
composer install
```
```console
php artisan key:generate
```
- Luego crear la base de datos con el nombre **datatables**, o
usar el nombre deseado, editando sus datos de conexión en el archivo **.env**,
una vez listo ejecutar los siguientes comandos:

```console
php artisan migrate:refresh --seed
```

- Finalmente levantar el servidor con:

```console
php artisan serve
```