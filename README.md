## Hola Brief tests

Apricación de prueba para evaluación de habilidades en Symfony.

## Módulos del sistema

1. **Productos**. Corresponde al manejo y administración de items disponibles en el sistema.
2. **Cupones**. Corresponde a la gestión de documento que puede ser intercambiado por descuentos al comprar un producto.
3. **Pedidos**. Corresponde al manejo de información de las operación de compra de productos.

## Casos de uso

1. **Producto** 
1.1. Registrar de producto
1.2. Ver lista de productos

2. **Cupon** 
2.1. Registrar de cupón
2.2. Ver lista de cupones

3. **Pedido** 
3.1. Registrar de pedido
3.2. Ver lista de pedidos

## Documentación

- [Documentación en Trello](https://trello.com/b/fP5F4IRf/holabrief-test).
- [Guía de estándares para el desarrollo del API REST](https://elbauldelprogramador.com/buenas-practicas-para-el-diseno-de-una-api-restful-pragmatica/#22-c%C3%B3digos-de-estado-http).
- [Cliente API | Postman Colección](https://www.getpostman.com/collections/68541573069e563a3a70).

## Run project

1. Crear entorno de Desarrollo
1.1. Crear archivo del entorno de desarrollo ".env.local"
1.2. Agregar las credenciales de la DB de desarrollo al archivo ".env.local"

2. Crear entorno de Pruebas
2.1. Agregar las credenciales de la DB de pruebas al archivo ".env.test"

3. Instalar dependencias
composer install

4. Ejecutar migración
php bin/console doctrine:migrations:migrate

5. Ejecutar Fixures (para las pruebas)
php bin/console doctrine:fixtures:load --env=test

6. Ejecutar Tests
php ./vendor/bin/phpunit