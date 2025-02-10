# BoardByte: Tienda de Juegos de Mesa

## Descripción del Proyecto

BoardByte es una tienda en línea de juegos de mesa desarrollada como parte de una actividad de clase. Este proyecto implementa conceptos clave como bases de datos SQL, el patrón de diseño Modelo-Vista-Controlador (MVC), y el Patrón Repositorio.
El proyecto se ha desarrollado siguiendo un enfoque inspirado en la estructura y convenciones de Laravel, adaptándolas a nuestro contexto específico.

## Características

- Listado de productos (juegos de mesa) con detalles como nombre, precio y descripción.
- Carrito de compras para agregar productos.
- Gestión de inventario (crear, actualizar, eliminar juegos).
- Sistema de pedidos con almacenamiento en base de datos.

## Tecnologías Utilizadas

- **PHP**: Lenguaje principal para la lógica de negocio y la gestión de vistas/controladores.
- **MySQL**: Sistema de gestión de bases de datos.
- **HTML, CSS, JavaScript**: Para el diseño e interactividad de la interfaz de usuario. JavaScript se utiliza para mejorar la experiencia del usuario en elementos específicos de la aplicación.
- **Patrón MVC**: Arquitectura del proyecto.
- **Patrón Repositorio**: Abstracción de operaciones de base de datos.

## Estructura del Proyecto
# Tienda de Juegos de Mesa (se llama boardbyte)

Este proyecto consiste en el desarrollo de una **Tienda de Juegos de Mesa**, diseñado como parte de una actividad de clase. Su propósito es implementar conceptos clave como bases de datos SQL, el patrón de diseño **Modelo-Vista-Controlador (MVC)**, y el **Patrón Repositorio**.

## Descripción del Proyecto

La tienda permite realizar las siguientes acciones:
- Listar productos disponibles (juegos de mesa) con detalles como nombre, precio y descripción.
- Agregar productos al carrito de compras.
- Gestionar el inventario de la tienda (crear, actualizar, eliminar juegos).
- Realizar pedidos y almacenarlos en la base de datos.

El proyecto se divide en capas según el patrón MVC, integrando el Patrón Repositorio para la abstracción de acceso a datos.

## Tecnologías Utilizadas

- **PHP**: Lenguaje principal para la lógica de negocio y la gestión de vistas/controladores.
- **MySQL**: Sistema de gestión de bases de datos para almacenar información de productos, usuarios, y pedidos.
- **HTML, CSS, JavaScript**: Para el diseño e interactividad de la interfaz de usuario. JavaScript se utiliza para mejorar la experiencia del usuario en elementos específicos de la aplicación.
- **Patrón MVC**: Organización de la arquitectura del proyecto.
- **Patrón Repositorio**: Abstracción y desacoplamiento de las operaciones de la base de datos.

## Estructura del Proyecto
boardbyte:.
- **app/**: Contiene el núcleo de la aplicación.
  - **controllers/**: Aloja los controladores que manejan la lógica de negocio.
  - **includes/**: Contiene archivos PHP reutilizables, como funciones auxiliares.
  - **models/**: Almacena las clases de modelos que representan las entidades de la aplicación.
  - **repositories/**: Contiene las clases de repositorio que manejan las operaciones de la base de datos.
  - **views/**: Almacena las plantillas de las vistas de la aplicación.

- **config/**: Contiene archivos de configuración, como la configuración de la base de datos.

- **public/**: Es el punto de entrada de la aplicación y contiene archivos accesibles públicamente.

- **resources/**: Almacena recursos estáticos y assets de la aplicación.
  - **commons/**: Contiene archivos comunes utilizados en múltiples partes de la aplicación.
  - **css/**: Almacena los archivos CSS para el estilo de la aplicación.
  - **images/**: Contiene las imágenes utilizadas en la aplicación.
    - **productos/**: Imágenes relacionadas con los productos (juegos de mesa).
      - **adicional/**: Imágenes adicionales de los productos.
      - **portadas/**: Imágenes de portada de los juegos.
    - **usuarios/**: Imágenes relacionadas con los usuarios, como avatares.
  - **js/**: Contiene los archivos JavaScript para la interactividad del lado del cliente.

Esta estructura sigue un patrón similar al de Laravel, adaptado a las necesidades específicas de nuestro proyecto BoardByte, facilitando la organización y mantenimiento del código.

## Enfoque de Desarrollo

- El proyecto sigue una estructura inspirada en Laravel, adaptando sus convenciones y buenas prácticas al contexto de PHP puro.
- Se ha implementado un sistema de rutas similar al de Laravel para manejar las peticiones HTTP.
- La organización de directorios y la nomenclatura de archivos siguen patrones familiares para los desarrolladores de Laravel.
- Se utiliza JavaScript para mejorar la interactividad en el frontend, especialmente en el carrito de compras y la gestión de productos.

## Ejecución
Solamente debes incluir el proyecto en algún procesador PHP. No se requiere más complejidad
