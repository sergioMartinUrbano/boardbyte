CREATE DATABASE boardbyte;
USE boardbyte;

CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) not null,
    usuario VARCHAR(100) unique not null,
    contrasena TEXT not null,
    correo VARCHAR(100) not null unique,
    telefono VARCHAR(15),
    direccion_1 TEXT,
    direccion_2 TEXT,
    direccion_3 TEXT,
    foto_perfil TEXT default 'default.jpg',
    rol ENUM('usuario', 'admin') default 'usuario'
);

CREATE TABLE Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) not null,
    descripcion_corta VARCHAR(255) not null,
    descripcion_larga TEXT not null,
    precio DECIMAL(10, 2) not null,
    foto_portada TEXT not null
);

CREATE TABLE Carrito (
    id_usuario INT,
    id_producto INT,
    cantidad INT DEFAULT 1,
    PRIMARY KEY (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE
);

CREATE TABLE Fotos_Productos (
    id_producto INT,
    foto VARCHAR(255),
    PRIMARY KEY (id_producto, foto),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE
);

CREATE TABLE Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_pedido DATE DEFAULT CURRENT_DATE,
    fecha_llegada DATE DEFAULT (CURRENT_DATE + INTERVAL 14 DAY),
    direccion TEXT,
    facturacion TEXT,
    estado ENUM('pendiente', 'enviado', 'procesando', 'cancelado', 'entregado') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);


CREATE TABLE Detalles_Pedido (
    id_pedido INT,
    id_producto INT,
    cantidad INT not null default 1,
    subtotal DECIMAL(10, 2),
    PRIMARY KEY (id_pedido, id_producto),
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE
);


-- Vistas 
CREATE VIEW Productos_Mas_Vendidos AS
SELECT 
    p.id_producto,
    p.nombre,
    p.descripcion_corta,
    p.foto_portada,
    SUM(dp.cantidad) AS cantidad_vendida
FROM 
    Detalles_Pedido dp
inner join 
    Productos p ON dp.id_producto = p.id_producto
GROUP BY 
    p.id_producto
ORDER BY 
    cantidad_vendida DESC
LIMIT 3;

CREATE VIEW Vista_Carrito AS
SELECT 
    c.id_usuario,
    c.id_producto,
    p.nombre AS nombre_producto,
    c.cantidad,
    (p.precio * c.cantidad) AS total,
    p.foto_portada,
    p.precio
FROM 
    Carrito c
inner join 
    Productos p
ON 
    c.id_producto = p.id_producto;

CREATE VIEW Vista_Detalles_Pedido AS
SELECT 
    dp.id_pedido,
    dp.id_producto,
    p.nombre AS nombre_producto,
    dp.cantidad,
    dp.subtotal
FROM 
    Detalles_Pedido dp
INNER JOIN 
    Productos p
ON 
    dp.id_producto = p.id_producto;


-- Procedimientos almacenados
DELIMITER $$
CREATE PROCEDURE GuardarCarrito(IN userId INT, IN productos JSON)
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE cantidad INT;
    DECLARE id_producto INT;
    DECLARE num_productos INT;
    SET num_productos = JSON_LENGTH(productos);
    DELETE FROM Carrito WHERE id_usuario = userId;
    WHILE i < num_productos DO
        SET id_producto = JSON_UNQUOTE(JSON_EXTRACT(productos, CONCAT('$[', i, '].id_producto')));
        SET cantidad = JSON_UNQUOTE(JSON_EXTRACT(productos, CONCAT('$[', i, '].cantidad')));
        IF id_producto IS NOT NULL AND cantidad IS NOT NULL THEN
            INSERT INTO Carrito (id_usuario, id_producto, cantidad)
            VALUES (userId, id_producto, cantidad);
        END IF;
        SET i = i + 1;
    END WHILE;
END $$
DELIMITER ;


INSERT INTO Usuarios (nombre, usuario, contrasena, correo, telefono, direccion_1, direccion_2, direccion_3, foto_perfil, rol) VALUES
('María García', 'maria_g','$2y$10$gCOGo6d1nQ4ph8QrTPHY4uQNjR9Lx7RIgW3/IsnH9qZ5zsfMC0mgq' , 'maria.garcia@email.com', '34612345678', 'Calle Mayor 1', 'Piso 2A', 'Madrid, 28001', 'maria_g.jpg', 'usuario'),
('Juan Rodríguez', 'juan_rod','$2y$10$8zCDeJ26HnxJvciK95zYJ.eUR5uM3keJsdNhzvb3C22kKYKYyRQM2' , 'juan.rodriguez@email.com', '34623456789', 'Avenida Diagonal 100', 'Apartamento 5B', 'Barcelona, 08019', 'juan_rod.jpg', 'usuario'),
('Ana Martínez', 'ana_m','$2y$10$WgKwMq5ILshvj8hjXVhSg.gcy45rkW6g7BBsv1ZADn/BVQNWLJ0oK' , 'ana.martinez@email.com', '34634567890', 'Calle Sierpes 25', NULL, 'Sevilla, 41004', 'ana_m.jpg', 'admin'),
('Carlos López', 'carlos_l','$2y$10$Sxk5KZV6sKdb3f.AlvX9Leb/G.65JWF/0bXV2HVMDD2CR4n2kqpxi' , 'carlos.lopez@email.com', '34645678901', 'Gran Vía 56', 'Piso 3C', 'Madrid, 28013', 'carlos_l.jpg', 'usuario'),
('Laura Fernández', 'laura_f','$2y$10$/3RyLoKOMNBaPlUHKiWsOu2/Z9EXq6Uq2XgiuRHMRIHNDi4ZvHRQO' , 'laura.fernandez@email.com', '34656789012', 'Paseo de Gracia 43', NULL, 'Barcelona, 08007', 'laura_f.jpg', 'usuario'),
('Pedro Sánchez', 'pedro_s','$2y$10$oSwy3TnQPpGcDyEeAqDl6OQkS3lLcBhcnrHUe6HgmM0xb57fzivma' , 'pedro.sanchez@email.com', '34667890123', 'Calle Larios 10', 'Local 2', 'Málaga, 29005', 'pedro_s.jpg', 'admin'),
('Miguel Torres', 'miguel_t','$2y$10$jiSUFGKmGV9K3cNTtY8OAuWk5Pi7d9VxP8vVGi3lPEZmDPoucX9Bi', 'miguel.torres@email.com', '34689012345', 'Plaza del Pilar 5', NULL, 'Zaragoza, 50003', 'miguel_t.jpg', 'usuario'),
('Carmen Ruiz', 'carmen_r','$2y$10$8zCDeJ26HnxJvciK95zYJ.eUR5uM3keJsdNhzvb3C22kKYKYyRQM2' , 'carmen.ruiz@email.com', '34690123456', 'Calle Triana 30', 'Piso 1A', 'Sevilla, 41010', 'carmen_r.jpg', 'usuario'),
('Javier Moreno', 'javier_m','$2y$10$8zCDeJ26HnxJvciK95zYJ.eUR5uM3keJsdNhzvb3C22kKYKYyRQM2' , 'javier.moreno@email.com', '34601234567', 'Paseo del Prado 15', 'Apartamento 7B', 'Madrid, 28014', 'javier_m.jpg', 'admin');

INSERT INTO productos (id_producto, nombre, descripcion_corta, descripcion_larga, precio, foto_portada) VALUES
(1, 'Catan', 'Juego de estrategia y comercio', 'Coloniza la isla de Catan en este clásico juego de estrategia. Construye asentamientos, ciudades y carreteras mientras comercias con recursos como madera, ladrillo, mineral, lana y grano. Negocia con otros jugadores, bloquea sus expansiones y desarrolla tu civilización para ser el primero en alcanzar 10 puntos de victoria. Con su combinación única de suerte, estrategia y habilidades de negociación, cada partida de Catan es una experiencia única y emocionante.', 39.99, 'Catan.jpg'),
(2, 'Carcassonne', 'Juego de colocación de losetas', 'Sumérgete en la Francia medieval construyendo un paisaje de ciudades, monasterios, caminos y campos. En cada turno, los jugadores colocan una loseta de terreno para expandir el mapa y pueden decidir colocar uno de sus seguidores en la loseta recién colocada. A medida que las características se completan, los jugadores ganan puntos. La estrategia radica en decidir dónde colocar las losetas y los seguidores para maximizar los puntos mientras se bloquea a los oponentes. Con su mecánica simple pero profunda, Carcassonne ofrece infinitas posibilidades tácticas.', 29.99, 'Carcassonne.jpg'),
(3, 'Ticket to Ride', 'Juego de construcción de rutas', 'Embárcate en una aventura ferroviaria a través de Europa en este emocionante juego de estrategia. Los jugadores compiten para conectar ciudades distantes mediante la recolección y juego de cartas de vagón de colores. Completa rutas secretas para ganar puntos adicionales, pero ten cuidado: otros jugadores pueden bloquear tus planes. Balancea la construcción de rutas cortas para puntos rápidos con rutas largas y arriesgadas para mayores recompensas. Con su mezcla perfecta de planificación y oportunismo, Ticket to Ride ofrece una experiencia accesible pero profundamente estratégica para jugadores de todas las edades.', 44.99, 'TickettoRide.jpg'),
(4, 'Pandemic', 'Juego cooperativo de salvar al mundo', 'Únete a un equipo de élite de especialistas en enfermedades para salvar a la humanidad en este intenso juego cooperativo. Trabaja junto a otros jugadores para contener y curar cuatro enfermedades mortales que amenazan con destruir la población mundial. Cada jugador asume un rol único con habilidades especiales, y deberán coordinar sus acciones para viajar por el mundo, tratar enfermedades, construir estaciones de investigación y descubrir curas. Con un reloj en cuenta regresiva y brotes impredecibles, cada decisión es crucial. Pandemic ofrece una experiencia tensa y gratificante que pone a prueba tu trabajo en equipo y habilidades de gestión de crisis.', 39.99, 'Pandemic.jpg'),
(5, 'Dixit', 'Juego de narración y adivinanzas', 'Adéntrate en un mundo de imaginación y creatividad con Dixit, un juego que desafía tu capacidad para contar historias y leer a los demás. En cada ronda, un jugador se convierte en el narrador, eligiendo una de sus hermosas cartas ilustradas y dando una pista sobre ella. Los demás jugadores seleccionan de sus propias manos la carta que mejor se ajuste a la pista. Todas las cartas se mezclan y revelan, y los jugadores deben adivinar cuál era la carta original del narrador. La clave está en dar pistas lo suficientemente vagas como para que algunos, pero no todos, adivinen correctamente. Con su arte evocador y su mecánica única, Dixit fomenta la creatividad y ofrece una experiencia diferente en cada partida.', 29.99, 'Dixit.jpg'),
(6, '7 Wonders', 'Juego de civilizaciones y desarrollo', 'Lidera una de las siete grandes ciudades del mundo antiguo en este aclamado juego de desarrollo de civilizaciones. A lo largo de tres eras, los jugadores deben gestionar recursos, desarrollar tecnologías, construir estructuras comerciales y militares, y por supuesto, erigir su maravilla. La mecánica de selección de cartas permite tomar decisiones estratégicas rápidas, mientras que la interacción con las ciudades vecinas añade un elemento de competencia indirecta. Con múltiples caminos hacia la victoria, ya sea a través del poderío militar, el avance científico, el comercio o la construcción de prestigiosos edificios, 7 Wonders ofrece una profunda experiencia estratégica en partidas de solo 30 minutos.', 49.99, '7Wonders.jpg'),
(7, 'Azul', 'Juego de colocación de azulejos', 'Conviértete en un artesano real en la corte del Rey Manuel I de Portugal en este elegante juego de colocación de azulejos. Los jugadores compiten para crear los patrones más hermosos y estratégicos en sus tableros personales, seleccionando cuidadosamente azulejos de colores de las fábricas centrales. La planificación es crucial: los azulejos deben colocarse en filas específicas y los puntos se otorgan por patrones y sets completos. Sin embargo, los azulejos no utilizados pueden resultar en penalizaciones. Con su combinación de estrategia profunda y componentes visualmente impresionantes, Azul ofrece una experiencia de juego satisfactoria y estéticamente placentera que captura la belleza de los azulejos decorativos portugueses.', 34.99, 'Azul.jpg'),
(8, 'Codenames', 'Juego de palabras y espías', 'Sumérgete en el mundo del espionaje y la comunicación críptica con Codenames. Dos equipos rivales de agentes secretos compiten para identificar a sus agentes en el campo utilizando solo pistas de una palabra. El Espía Jefe de cada equipo debe dar pistas que se relacionen con múltiples palabras en la cuadrícula, mientras evita las palabras del equipo contrario y el temido asesino. Los compañeros de equipo deben descifrar estas pistas crípticas y adivinar correctamente las palabras correspondientes. Con su mezcla única de deducción, asociación de palabras y tensión, Codenames ofrece una experiencia de juego emocionante y accesible que pone a prueba tu ingenio y tu capacidad para pensar lateralmente.', 19.99, 'Codenames.jpg'),
(9, 'Scythe', 'Juego de estrategia y conquista', 'Adéntrate en un mundo alternativo de los años 20, donde la Gran Guerra nunca terminó, en este épico juego de estrategia. Lidera tu facción en un paisaje dieselpunk de Europa del Este, utilizando mechas gigantes y tecnología steampunk para conquistar territorios, recolectar recursos y expandir tu imperio. Cada facción tiene habilidades únicas y un tablero de jugador asimétrico, ofreciendo diferentes estilos de juego. Balancea la producción de recursos, el desarrollo tecnológico, el despliegue de unidades y la conquista de territorios mientras navegas por las complejas relaciones con otras facciones. Con su impresionante arte, componentes de alta calidad y profundidad estratégica, Scythe ofrece una experiencia de juego inmersiva y altamente rejugable.', 79.99, 'Scythe.jpg'),
(10, 'Terraforming Mars', 'Juego de colonización espacial', 'Lidera una corporación en la misión de hacer Marte habitable en este juego de estrategia científica y económica. A lo largo de generaciones, los jugadores trabajan para aumentar la temperatura, el oxígeno y los océanos del planeta rojo, mientras desarrollan infraestructuras y proyectos únicos. Cada turno presenta decisiones cruciales: ¿invertir en nuevas tecnologías, lanzar proyectos ambiciosos o sabotear a la competencia? La gestión de recursos, la planificación a largo plazo y la adaptación a las cambiantes condiciones del planeta son clave para el éxito. Con cientos de cartas únicas y múltiples estrategias viables, Terraforming Mars ofrece una experiencia profunda y temáticamente rica que captura la emoción y los desafíos de la colonización interplanetaria.', 69.99, 'TerraformingMars.jpg'),
(11, 'Splendor', 'Juego de adquisición de gemas', 'Sumérgete en el Renacimiento como un rico mercader en este elegante juego de desarrollo económico. Los jugadores compiten para adquirir minas de gemas, medios de transporte y tiendas, representados por cartas con hermosas ilustraciones. Comienza con recursos limitados y gradualmente construye un motor económico, utilizando fichas de gemas para comprar cartas que proporcionan bonificaciones permanentes y puntos de prestigio. La estrategia radica en equilibrar la adquisición de gemas a corto plazo con inversiones a largo plazo en cartas de mayor valor. Con su mecánica simple pero profunda y su corta duración, Splendor ofrece una experiencia accesible pero altamente adictiva que captura la esencia del comercio y la riqueza renacentista.', 39.99, 'Splendor.jpg'),
(12, 'Puerto Rico', 'Juego de gestión de recursos coloniales', 'Viaja al Caribe del siglo XVII y desarrolla la próspera colonia de Puerto Rico en este clásico juego de estrategia económica. Como gobernador colonial, debes gestionar plantaciones, producir y comerciar recursos valiosos como café, azúcar y tabaco, y construir edificios que mejoren tu economía y te otorguen ventajas únicas. La innovadora mecánica de selección de roles añade un elemento estratégico adicional, ya que cada jugador elige una acción que todos realizarán, pero con un beneficio especial para el selector. Balancea la producción, el comercio y la construcción mientras compites por los envíos limitados a Europa. Con su profundidad estratégica y múltiples caminos hacia la victoria, Puerto Rico ofrece una experiencia de juego rica y desafiante que ha resistido la prueba del tiempo.', 44.99, 'PuertoRico.jpg'),
(13, 'Dominion', 'Juego de construcción de mazos', 'Construye tu reino medieval carta a carta en este innovador juego que popularizó el género de construcción de mazos. Comenzando con un pequeño conjunto de cartas, los jugadores adquieren nuevas cartas de un suministro común para mejorar su mazo y generar acciones, compras y monedas más poderosas. Cada partida es única, ya que solo se utilizan 10 de los muchos tipos de cartas del reino disponibles. La estrategia radica en construir combinaciones eficientes de cartas, equilibrar las acciones con la capacidad de compra, y adaptar tu estrategia a las cartas disponibles y a las tácticas de tus oponentes. Con su gran variedad de cartas y configuraciones posibles, Dominion ofrece una rejugabilidad casi infinita y una experiencia de juego que es fácil de aprender pero difícil de dominar.', 44.99, 'Dominion.jpg'),
(14, 'Cluedo', 'Juego de misterio y deducción', 'Adéntrate en una mansión llena de intriga y resuelve un misterioso asesinato en este clásico juego de deducción. Los jugadores asumen el papel de sospechosos, moviéndose por las habitaciones de la mansión Tudor, haciendo preguntas y recopilando pistas. Tu objetivo es descubrir quién cometió el crimen, con qué arma y en qué habitación. Utiliza tu habilidad de deducción para eliminar posibilidades y ser el primero en resolver el caso. La tensión aumenta a medida que los jugadores intercambian información y hacen acusaciones. Con su combinación única de estrategia, suerte y habilidades de deducción, Cluedo ofrece una experiencia emocionante que pone a prueba tus habilidades de detective y te sumerge en un mundo de misterio y suspense.', 24.99, 'Cluedo.jpg'),
(15, 'Risk', 'Juego de estrategia militar global', 'Conquista el mundo en este icónico juego de estrategia militar y diplomacia. Los jugadores despliegan sus ejércitos en un mapa mundial, planificando ataques, fortificando territorios y formando alianzas temporales. La mecánica de dados añade un elemento de azar a las batallas, mientras que las cartas de territorio proporcionan bonificaciones estratégicas. La clave del éxito radica en el equilibrio entre la expansión agresiva y la defensa prudente, así como en la habilidad para negociar y romper alianzas en el momento oportuno. Con su mezcla de estrategia a largo plazo y tácticas a corto plazo, Risk ofrece una experiencia épica y tensa que puede durar horas, capturando la emoción y la imprevisibilidad de la guerra global.', 29.99, 'Risk.jpg'),
(16, 'Agricola', 'Juego de gestión de granjas', 'Sumérgete en la vida rural de la Europa medieval en este profundo juego de gestión de recursos. Como granjero, debes desarrollar tu pequeña granja familiar, cultivando campos, criando animales y expandiendo tu hogar. Cada ronda presenta decisiones cruciales sobre cómo asignar tus limitados trabajadores: ¿recolectar recursos, construir mejoras o asegurar alimentos para tu familia? La presión por alimentar a tu familia en cada cosecha añade tensión, mientras que las cartas de ocupación y mejoras proporcionan estrategias únicas en cada partida. Con su rica temática, múltiples caminos hacia la victoria y la constante lucha contra la escasez, Agricola ofrece una experiencia de juego profunda y satisfactoria que recompensa la planificación cuidadosa y la adaptabilidad.', 59.99, 'Agricola.jpg'),
(17, 'Stone Age', 'Juego de civilizaciones primitivas', 'Viaja a la prehistoria y guía a tu tribu desde sus humildes inicios hasta convertirse en una próspera civilización en este juego de colocación de trabajadores. Como líder tribal, debes asignar sabiamente a tus miembros para recolectar recursos, cazar, aumentar tu población y desarrollar nuevas tecnologías. La mecánica de dados añade un elemento de suerte a la recolección de recursos, mientras que el tablero de puntuación incentiva una estrategia equilibrada. Deberás gestionar cuidadosamente tus recursos, decidir cuándo expandir tu población y qué tecnologías priorizar. Con su temática inmersiva, componentes de alta calidad y mecánicas bien integradas, Stone Age ofrece una experiencia accesible pero estratégicamente rica que captura la esencia de la lucha por la supervivencia y el progreso en la Edad de Piedra.', 49.99, 'StoneAge.jpg'),
(18, 'Secret Hitler', 'Juego de deducción y estrategia en la Alemania pre-nazi', 'En Secret Hitler, los jugadores se encuentran en la Alemania de los años 30, luchando por el control del país en un escenario político tenso. Los jugadores se dividen en dos facciones: los liberales y los fascistas. Mientras los liberales intentan aprobar leyes que protejan la democracia, los fascistas conspiran en secreto para instaurar un régimen autoritario, y uno de ellos es nada menos que Hitler. A lo largo de la partida, los jugadores deben usar su astucia para descubrir quién está del lado de quién, mientras intentan pasar leyes sin revelar demasiado sobre sus intenciones. El juego es una emocionante mezcla de estrategia, engaño y deducción, donde cada elección puede cambiar el rumbo de la historia. Con una tensión constante y una rejugabilidad alta, Secret Hitler es perfecto para quienes disfrutan de juegos de roles y traiciones. ¡Pero cuidado! Si los fascistas consiguen aprobar suficientes leyes o eligen a Hitler como canciller en el momento equivocado, habrán ganado!', 24.99, 'SecretHitler.jpg'),
(19, 'Small World', 'Juego de civilizaciones fantásticas', 'Sumérgete en un mundo de fantasía donde diversas razas compiten por el control de un territorio demasiado pequeño para todos. En Small World, los jugadores eligen combinaciones únicas de razas fantásticas (como elfos, orcos, o gnomos) y poderes especiales, utilizándolas para conquistar regiones en un mapa que cambia con cada partida. La clave está en reconocer cuándo tu civilización ha alcanzado su apogeo y ponerla en declive para dar paso a una nueva. Gestiona sabiamente tus fichas limitadas, aprovecha las habilidades únicas de cada raza y poder, y adapta tu estrategia a las acciones de tus oponentes. Con su arte colorido, componentes de calidad y mecánicas que fomentan la interacción y el conflicto, Small World ofrece una experiencia de juego accesible pero estratégicamente profunda que garantiza risas y diversión en cada partida.', 49.99, 'SmallWorld.jpg'),
(20, 'Mysterium', 'Juego cooperativo de deducción', 'Adéntrate en un mundo de misterio y comunicación etérea en este innovador juego cooperativo. Un jugador asume el papel de un fantasma que intenta guiar a un grupo de médiums para resolver su propio asesinato. Incapaz de hablar, el fantasma solo puede comunicarse a través de cartas de visión surrealistas y oníricas. Los médiums deben interpretar estas visiones crípticas para identificar al culpable, el lugar del crimen y el arma utilizada. Con un límite de tiempo que aumenta la tensión, los jugadores deben trabajar juntos, discutir sus interpretaciones y llegar a un consenso antes de que sea demasiado tarde. El arte evocador y atmosférico de las cartas de visión, combinado con la mecánica única de comunicación limitada, crea una experiencia inmersiva y emocionante. Mysterium ofrece un desafío cooperativo único que pone a prueba la intuición y la capacidad de interpretación de los jugadores.', 44.99, 'Mysterium.jpg'),
(21, 'Twilight Imperium', 'Juego épico de conquista espacial', 'Embárcate en una saga galáctica de proporciones épicas en este monumental juego de estrategia 4X (eXplorar, eXpandir, eXplotar y eXterminar). Como líder de una de las diecisiete facciones únicas, deberás guiar a tu civilización desde los confines de tu sistema natal hasta dominar la galaxia. Explora nuevos sistemas, coloniza planetas, desarrolla tecnologías avanzadas, construye poderosas flotas y participa en complejas negociaciones diplomáticas. Cada facción tiene habilidades y tecnologías únicas que definen su estilo de juego, desde los belicosos Barony of Letnev hasta los misteriosos Naalu Collective. Las mecánicas de juego abarcan desde el combate espacial y la política galáctica hasta el comercio y la investigación científica. Con partidas que pueden durar un día entero y una profundidad estratégica incomparable, Twilight Imperium ofrece una experiencia de juego inmersiva y épica que captura la grandeza de la ciencia ficción espacial.', 149.99, 'TwilightImperium.jpg'),
(22, 'Concordia', 'Juego de comercio en el Imperio Romano', 'Viaja por el Imperio Romano en su apogeo, estableciendo una red comercial y expandiendo tu influencia en este elegante juego de estrategia económica. Como un comerciante romano, deberás producir y comerciar recursos como vino, tela y herramientas, mientras estableces colonias en ciudades a lo largo del Mediterráneo. La mecánica central del juego gira en torno a un innovador sistema de cartas de personaje: cada turno, juegas una carta que determina tus acciones, y solo recuperas tus cartas cuando juegas la carta de Tribune. Esta mecánica crea un fascinante rompecabezas de planificación y timing. Los puntos se calculan de manera ingeniosa al final del juego, basándose en los tipos de cartas que has adquirido y cómo se alinean con tu imperio. Con reglas simples pero profundas consecuencias estratégicas, Concordia ofrece una experiencia de juego pulida y satisfactoria que recompensa la planificación a largo plazo y la adaptabilidad.', 64.99, 'Concordia.jpg'),
(23, 'Viticulture', 'Juego de gestión de viñedos', 'Sumérgete en el mundo de la viticultura en la pintoresca campiña toscana con este encantador juego de gestión de trabajadores. Como propietario de un pequeño viñedo, deberás guiar tu negocio familiar desde sus humildes comienzos hasta convertirlo en una bodega de renombre mundial. Planta viñedos, cosecha uvas, elabora vino y cumple con los pedidos de los visitantes para ganar puntos de victoria. La mecánica de colocación de trabajadores se combina con un innovador sistema de "despertar" que añade profundidad a la toma de decisiones: ¿madrugarás para ser el primero en elegir acciones, o dormirás hasta tarde para obtener bonificaciones? Gestiona cuidadosamente tus recursos limitados, contrata trabajadores temporales en las temporadas ocupadas y construye estructuras que mejoren tu producción. Con su temática inmersiva, componentes de alta calidad y mecánicas bien integradas, Viticulture ofrece una experiencia de juego rica y satisfactoria que captura la esencia de la vida en un viñedo italiano.', 59.99, 'Viticulture.jpg'),
(24, 'Wingspan', 'Juego de colección de aves', 'Adéntrate en el fascinante mundo de la ornitología en este hermoso y estratégico juego de tablero. Como un entusiasta de las aves, tu objetivo es atraer la mayor variedad de aves a tu santuario. Cada ave es única, con habilidades especiales que se activan en cascada, creando combos satisfactorios a medida que juegas. Deberás gestionar cuidadosamente tus recursos de comida y huevos para jugar cartas de aves, mientras cumples objetivos de final de ronda y personales. La mecánica de colocación de dados añade un elemento de azar mitigado, mientras que la variedad de estrategias viables garantiza una alta rejugabilidad. Con su impresionante arte realista, componentes de alta calidad y datos científicos precisos en cada carta, Wingspan no solo es un excelente juego de estrategia, sino también una herramienta educativa sobre la diversidad aviar. Este juego ofrece una experiencia relajante pero profundamente estratégica que atrae tanto a jugadores experimentados como a principiantes.', 59.99, 'Wingspan.jpg'),
(25, 'Brass: Birmingham', 'Juego de revolución industrial', 'Sumérgete en el corazón de la Revolución Industrial en este profundo juego de estrategia económica. Ambientado en Birmingham durante el auge de la industria, los jugadores compiten para construir una red de fábricas, canales y ferrocarriles, produciendo y vendiendo recursos como carbón, hierro y cerveza. La mecánica central del juego gira en torno a un sistema de cartas que representan ubicaciones donde puedes construir o vender, creando un fascinante rompecabezas de oportunidades y limitaciones. El juego se desarrolla en dos eras distintas, cada una con sus propias reglas y desafíos, reflejando los cambios históricos de la época. La interconexión entre los jugadores es crucial: puedes beneficiarte de las industrias de tus oponentes, pero también debes competir por recursos y mercados limitados. Con su profundidad estratégica, hermosa presentación y fiel representación histórica, Brass: Birmingham ofrece una experiencia de juego rica y satisfactoria que recompensa la planificación a largo plazo y la adaptabilidad.', 79.99, 'BrassBirmingham.jpg'),
(26, 'Gloomhaven', 'Juego de aventuras y mazmorras', 'Embárcate en una épica campaña de fantasía en este masivo juego de aventuras. Gloomhaven es un mundo en constante cambio, lleno de mazmorras, monstruos y misterios por descubrir. Los jugadores asumen el papel de aventureros errantes, cada uno con sus propias habilidades únicas y motivaciones. A medida que exploras mazmorras y completas misiones, la ciudad de Gloomhaven y el mundo que la rodea evolucionan, desbloqueando nuevos escenarios, clases de personajes y elementos de la trama. El innovador sistema de combate basado en cartas elimina la necesidad de dados, ofreciendo un control estratégico sobre las acciones de tu personaje. Cada decisión, desde qué misión aceptar hasta qué cartas jugar en combate, tiene consecuencias duraderas que dan forma a tu experiencia única. Con cientos de horas de juego, una narrativa ramificada y un mundo que reacciona a tus acciones, Gloomhaven ofrece una experiencia de juego inmersiva y épica que redefine el género de los juegos de campaña.', 139.99, 'Gloomhaven.jpg'),
(27, 'Patchwork', 'Juego de costura abstracto', 'Sumérgete en el acogedor mundo de la confección de colchas en este elegante juego de dos jugadores. En Patchwork, los jugadores compiten para crear la colcha más bella y eficiente utilizando piezas de diferentes formas y tamaños. El mecanismo central del juego gira en torno a un innovador sistema de economía de tiempo: cada pieza cuesta no solo botones (la moneda del juego), sino también tiempo. Debes equilibrar cuidadosamente la adquisición de piezas valiosas con el avance en el tablero de tiempo, ya que ser el último en el tablero te permite tomar más turnos. La estrategia radica en seleccionar las piezas que mejor se ajustan a tu tablero, maximizando el espacio y los ingresos de botones, mientras bloqueas las opciones de tu oponente. Con su temática única, mecánicas pulidas y profundidad estratégica sorprendente para un juego tan compacto, Patchwork ofrece una experiencia de juego satisfactoria y adictiva que atrae tanto a jugadores casuales como a estrategas experimentados.', 29.99, 'Patchwork.jpg'),
(28, 'Santorini', 'Juego de estrategia abstracta', 'Transporta tu mente a las soleadas islas griegas en este hermoso y accesible juego de estrategia abstracta. En Santorini, los jugadores compiten para ser los primeros en construir y coronar una torre de tres pisos en un tablero tridimensional que evoca la arquitectura de las Cícladas. Cada turno, mueves uno de tus dos trabajadores y luego construyes un nivel en un espacio adyacente. La simplicidad de estas reglas básicas oculta una profundidad estratégica sorprendente, ya que debes planificar tus movimientos cuidadosamente para bloquear a tu oponente mientras avanzas hacia la victoria. El juego se eleva a un nuevo nivel de complejidad con la adición de cartas de dios, que otorgan a cada jugador un poder único que altera las reglas del juego. Con su combinación de reglas simples, profundidad estratégica y componentes visualmente impresionantes, Santorini ofrece una experiencia de juego accesible pero profundamente satisfactoria que atrae tanto a familias como a jugadores experimentados.', 34.99, 'Santorini.jpg'),
(29, 'Hive', 'Juego de estrategia con insectos', 'Adéntrate en el fascinante mundo de los insectos en este elegante juego de estrategia abstracta para dos jugadores. En Hive, los jugadores se turnan para colocar y mover piezas hexagonales que representan diferentes insectos, cada uno con su propio patrón de movimiento único. El objetivo es rodear completamente la abeja reina del oponente. La mecánica sin tablero permite que el "tablero" crezca y se deforme orgánicamente a medida que se desarrolla el juego, creando situaciones únicas en cada partida. Debes pensar cuidadosamente sobre cuándo introducir cada insecto y cómo utilizar sus movimientos especiales de manera efectiva: las hormigas pueden deslizarse a cualquier espacio accesible, los escarabajos pueden trepar sobre otras piezas, las arañas se mueven exactamente tres espacios, y los saltamontes pueden saltar sobre otras piezas. Con sus reglas simples pero profundas consecuencias estratégicas, componentes duraderos y portabilidad, Hive ofrece una experiencia de juego intensa y satisfactoria que se puede disfrutar en cualquier lugar.', 24.99, 'Hive.jpg'),
(30, 'Sagrada', 'Juego de creación de vitrales', 'Sumérgete en el mundo del arte sacro y la creación de vitrales en este hermoso juego de dados y patrones. En Sagrada, los jugadores compiten para crear las vidrieras más impresionantes utilizando dados de colores como piezas de vidrio. Cada jugador tiene un tablero de patrón único que dicta dónde se pueden colocar los dados, basándose en el color y el valor. La mecánica central del juego gira en torno a la selección y colocación de dados: debes equilibrar cuidadosamente las restricciones de tu patrón con los objetivos públicos y privados que otorgan puntos adicionales. Las herramientas proporcionan formas de mitigar la suerte de los dados, añadiendo una capa adicional de decisiones estratégicas. Con su temática única, componentes visualmente impresionantes y un equilibrio perfecto entre suerte y estrategia, Sagrada ofrece una experiencia de juego accesible pero prof
unda que atrae tanto a jugadores casuales como a estrategas experimentados.', 39.99, 'Sagrada.jpg'),
(31, 'Tzolk`in: El Calendario Maya', 'Juego de engranajes y planificación', 'Sumérgete en el mundo de la antigua civilización maya en este innovador juego de gestión de trabajadores. El corazón de Tzolk`in es un impresionante mecanismo de engranajes interconectados que representa el calendario sagrado maya. Los jugadores colocan sus trabajadores en los engranajes, que giran cada ronda, moviendo a los trabajadores a nuevas acciones más poderosas. Esta mecánica única crea un fascinante rompecabezas de planificación temporal: debes anticipar dónde estarán tus trabajadores en rondas futuras y cómo se alinearán con tus estrategias. Recolecta recursos, construye monumentos, desarrolla tecnologías y haz ofrendas a los dioses para ganar puntos de victoria. El juego se desarrolla en un delicado equilibrio entre acciones inmediatas y planificación a largo plazo. Con su mecánica innovadora, profundidad estratégica y una representación respetuosa de la cultura maya, Tzolk`in ofrece una experiencia de juego única y desafiante que recompensa el pensamiento anticipado y la adaptabilidad.', 59.99, 'Tzolkin.jpg'),
(32, 'Cosmic Encounter', 'Juego de negociación espacial', 'Embárcate en una odisea galáctica de diplomacia, traición y conquista en este clásico juego de negociación y conflicto. En Cosmic Encounter, cada jugador lidera una raza alienígena única con poderes que rompen las reglas, intentando establecer colonias en los sistemas planetarios de sus oponentes. El corazón del juego son los encuentros: confrontaciones entre jugadores que se resuelven a través de cartas de ataque y la crucial capacidad de pedir ayuda a otros jugadores. Esta mecánica fomenta alianzas temporales, negociaciones tensas y traiciones dramáticas. La gran variedad de razas alienígenas, cada una con un poder único que altera fundamentalmente las reglas del juego, garantiza que cada partida sea una experiencia completamente nueva. Con su mezcla perfecta de estrategia, suerte, negociación y caos cósmico, Cosmic Encounter ofrece una experiencia de juego altamente interactiva y memorable que ha influido en generaciones de diseñadores de juegos y sigue siendo tan fresco y emocionante como cuando se lanzó por primera vez.', 59.99, 'CosmicEncounter.jpg'),
(33, 'Mage Knight', 'Juego de aventuras y exploración', 'Embárcate en una épica aventura de fantasía en este profundo y complejo juego de exploración y conquista. Como uno de los poderosos Mage Knights, deberás explorar un reino misterioso, luchar contra monstruos, reclutar seguidores, aprender poderosos hechizos y conquistar ciudades. El corazón del juego es un innovador sistema de construcción de mazos: comienzas con un mazo básico de cartas que representan tus acciones, y a medida que el juego avanza, adquieres nuevas y poderosas cartas que expanden tus capacidades. Cada turno presenta un rompecabezas táctico mientras intentas maximizar el uso de tus cartas para moverte, luchar y interactuar con el mundo del juego. El tablero modular y los escenarios variados garantizan una alta rejugabilidad, mientras que las opciones para juego en solitario, cooperativo y competitivo ofrecen múltiples formas de experimentar el juego. Con su profundidad estratégica, narrativa emergente y mecánicas ricamente entrelazadas, Mage Knight ofrece una experiencia de juego inmersiva y desafiante que recompensa el pensamiento táctico y la planificación a largo plazo.', 79.99, 'MageKnight.jpg'),
(34, 'Mechs vs. Minions', 'Juego cooperativo de programación', 'Sumérgete en el colorido y caótico mundo de League of Legends con este emocionante juego de mesa cooperativo. En Mechs vs. Minions, los jugadores controlan yordles pilotando mechs, trabajando juntos para completar misiones y derrotar a hordas de minions. El corazón del juego es un innovador sistema de programación: cada turno, los jugadores seleccionan cartas de comando para programar las acciones de sus mechs. Sin embargo, el daño recibido puede dañar tu línea de comandos, insertando instrucciones aleatorias y creando situaciones hilarantes y caóticas. Las misiones se desarrollan a lo largo de una campaña narrativa, introduciendo gradualmente nuevas mecánicas y desafíos. La cooperación es crucial, ya que los jugadores deben coordinar sus acciones para superar obstáculos y derrotar a los jefes. Con sus componentes de alta calidad, incluidos mechs detallados y miniaturas de minions, una narrativa envolvente y mecánicas que fomentan tanto la planificación estratégica como la adaptación al caos, Mechs vs. Minions ofrece una experiencia de juego cooperativa única y altamente rejugable.', 79.99, 'MechsVsMinions.jpg'),
(35, 'Mansiones de la Locura', 'Juego de horror lovecraftiano', 'Adéntrate en un mundo de horror cósmico y misterio en este atmosférico juego de exploración y narrativa. Basado en el universo de H.P. Lovecraft, Mansiones de la Locura lleva a los jugadores a explorar lugares embrujados, desentrañar complejas tramas y enfrentarse a horrores indescriptibles. Lo que hace único a este juego es su innovador uso de una aplicación digital que actúa como maestro del juego, controlando la narrativa, los enemigos y los rompecabezas, permitiendo una experiencia totalmente cooperativa. Los jugadores asumen el papel de investigadores, cada uno con habilidades únicas, trabajando juntos para resolver el misterio antes de que sea demasiado tarde. La aplicación genera escenarios dinámicos, asegurando que cada partida sea una experiencia única y sorprendente. A medida que exploras las siniestras ubicaciones, deberás recoger pistas, resolver acertijos y combatir contra cultistas y criaturas sobrenaturales. Con su rica narrativa, componentes de alta calidad y la fusión perfecta de elementos físicos y digitales, Mansiones de la Locura ofrece una experiencia de juego inmersiva y atmosférica que captura la esencia del horror lovecraftiano.', 99.99, 'MansionesDeLaLocura.jpg'),
(36, 'Zombicide', 'Juego cooperativo de supervivencia', 'Sumérgete en la acción trepidante de un apocalipsis zombie en este emocionante juego cooperativo de supervivencia. En Zombicide, los jugadores asumen el papel de supervivientes ordinarios que deben trabajar juntos para completar misiones y mantenerse con vida en un mundo infestado de muertos vivientes. El juego se desarrolla en un tablero modular que representa diferentes escenarios urbanos, desde calles y edificios hasta alcantarillas. Los supervivientes deben buscar armas y equipo, eliminar zombies y cumplir objetivos específicos de la misión. Lo que hace único a Zombicide es su sistema de experiencia: a medida que los jugadores matan zombies y completan objetivos, sus personajes se vuelven más poderosos, desbloqueando nuevas habilidades. Sin embargo, esto también aumenta el nivel de peligro, haciendo que aparezcan zombies más numerosos y peligrosos. La tensión aumenta constantemente, obligando a los jugadores a equilibrar la necesidad de completar objetivos con la de mantenerse con vida. Con sus miniaturas detalladas, escenarios variados y mecánicas que fomentan la cooperación y el heroísmo, Zombicide ofrece una experiencia de juego intensa y cinematográfica que captura la esencia de las películas de zombies.', 79.99, 'Zombicide.jpg'),
(37, 'Kingdomino', 'Juego de construcción de reinos', 'Construye tu reino medieval en este encantador y accesible juego de colocación de fichas. En Kingdomino, los jugadores compiten para crear el reino más valioso combinando diferentes tipos de terreno en un tablero de 5x5. El juego utiliza una mecánica innovadora inspirada en el dominó: cada turno, los jugadores seleccionan una ficha de dominó que consta de dos terrenos diferentes y la añaden a su reino, siguiendo reglas simples de colocación. La clave está en la selección de fichas: elegir una ficha valiosa puede significar una posición menos favorable para elegir en el siguiente turno. Debes equilibrar la creación de grandes áreas del mismo tipo de terreno con la recolección de coronas, que multiplican el valor de esas áreas. Con sus reglas simples, partidas rápidas y un elemento de planificación espacial, Kingdomino ofrece una experiencia de juego satisfactoria y accesible que atrae tanto a familias como a jugadores experimentados. Su éxito ha llevado a varias expansiones y variantes que añaden nuevas capas de complejidad para aquellos que buscan un desafío adicional.', 19.99, 'Kingdomino.jpg'),
(38, 'Growing Season', 'Juego de construcción agrícola y estrategia', 'En Growing Season, los jugadores se convierten en granjeros en busca de expandir y optimizar sus tierras en un competitivo mercado agrícola. A lo largo de la partida, deberás plantar, cosechar y vender tus cultivos para obtener el mayor beneficio posible, mientras gestionas tus recursos y mejoras tus tierras. Lo único que puede hacer que tu cosecha sea aún más fructífera es adaptarte a las estaciones y a las condiciones cambiantes del mercado, todo mientras compites contra otros jugadores por obtener los mejores rendimientos. Con mecánicas profundas de estrategia y una experiencia rica en decisiones tácticas, Growing Season pone a prueba tu capacidad para planificar a largo plazo y reaccionar a las circunstancias del momento. Su combinación de decisiones a corto y largo plazo, junto con la gestión de recursos y la competencia directa, hace de Growing Season un juego desafiante y gratificante para los amantes de los juegos de estrategia y simulación agrícola.', 49.99, 'GrowingSeason.jpg'),
(39, 'Terra Mystica', 'Juego de civilizaciones fantásticas', 'Sumérgete en un mundo de magia y desarrollo en este profundo juego de estrategia y gestión de recursos. En Terra Mystica, los jugadores lideran una de catorce facciones fantásticas, cada una con habilidades únicas y preferencias de terreno. Tu objetivo es expandir tu civilización transformando el paisaje para que se adapte a tu facción, construyendo estructuras y desarrollando tu poder mágico. El juego se desarrolla en un mapa hexagonal donde la proximidad a otros jugadores es crucial: estar cerca te permite beneficiarte de sus acciones, pero también aumenta la competencia por el espacio limitado. La mecánica central gira en torno a un sistema de gestión de recursos interconectados: trabajadores, monedas, poder mágico y sacerdotes, cada uno vital para diferentes aspectos del juego. Debes equilibrar cuidadosamente la expansión territorial, el desarrollo tecnológico y la acumulación de poder cultista para maximizar tus puntos de victoria. Con su profundidad estratégica, alta interactividad y la asimetría entre facciones, Terra Mystica ofrece una experiencia de juego rica y desafiante que recompensa la planificación a largo plazo y la adaptabilidad.', 79.99, 'TerraMystica.jpg'),
(40, 'El Grande', 'Juego de influencia en la España medieval', 'Viaja a la España del siglo XV en este clásico juego de control de área y manipulación de poder. En El Grande, los jugadores asumen el papel de Grandes, poderosos nobles que compiten por influencia en las diferentes regiones de España. El corazón del juego es un sistema de subastas y colocación de caballeros: cada ronda, los jugadores pujan por el orden de turno y las cartas de acción usando un suministro limitado de poder. Luego, utilizan estas cartas para mover sus caballeros por el mapa, intentando asegurar la mayoría en regiones valiosas. La torre del castillo añade un elemento de sorpresa, permitiendo movimientos secretos que se revelan al final de cada ronda de puntuación. La estrategia radica en saber cuándo expandirse agresivamente y cuándo consolidar tu posición, siempre consciente de las posibles acciones de tus oponentes. Con su mezcla de planificación a largo plazo, tácticas oportunistas y negociación sutil, El Grande ofrece una experiencia de juego tensa y satisfactoria que ha resistido la prueba del tiempo, influyendo en generaciones de juegos de control de área.', 69.99, 'ElGrande.jpg'),
(41, 'Tigris y Éufrates', 'Juego de civilizaciones antiguas', 'Retrocede en el tiempo hasta el nacimiento de la civilización en este profundo juego de estrategia abstracta. Ambientado en el creciente fértil entre los ríos Tigris y Éufrates, los jugadores compiten para construir y liderar las civilizaciones más prósperas de la antigüedad. Cada jugador controla cuatro líderes diferentes (rey, sacerdote, granjero y comerciante), cada uno asociado a un tipo específico de ficha y una forma de ganar puntos. El juego se desarrolla en un tablero abstracto donde los jugadores colocan fichas para construir reinos, monumentos y maravillas. Los conflictos surgen cuando los reinos se conectan, llevando a guerras o revoluciones que pueden cambiar drásticamente el control de las regiones. La mecánica de puntuación es única: tu puntuación final está determinada por tu categoría más débil, fomentando un desarrollo equilibrado. Con su profundidad estratégica, alta interactividad y la tensión constante entre expansión y consolidación, Tigris y Éufrates ofrece una experiencia de juego rica y desafiante que recompensa el pensamiento táctico y la adaptabilidad.', 59.99, 'TigrisYEufrates.jpg'),
(42, 'Caverna', 'Juego de gestión de cuevas y granjas', 'Sumérgete en la vida de un clan de enanos en este profundo juego de gestión de recursos y desarrollo. En Caverna, los jugadores guían a una familia de enanos en la tarea de cavar una cueva-hogar en la ladera de una montaña y establecer una pequeña granja en el bosque exterior. El juego combina elementos de gestión de trabajadores, agricultura y exploración de mazmorras. Cada ronda, debes decidir cómo asignar a tus enanos limitados: ¿excavarás más profundo en la montaña, cultivarás cultivos, criarás animales, recolectarás recursos del bosque o te aventurarás en expediciones? A medida que tu clan crece, deberás alimentarlos y expandir tu cueva para acomodarlos. El juego ofrece una gran variedad de estrategias viables, desde enfocarse en la agricultura y la cría de animales hasta especializarse en la minería y la fabricación de armas. Con su vasta cantidad de opciones, componentes detallados y la satisfacción de ver crecer tu cueva y granja, Caverna ofrece una experiencia de juego rica y inmersiva que recompensa la planificación a largo plazo y la eficiencia.', 89.99, 'Caverna.jpg'),
(43, 'Alquimistas', 'Juego de deducción y experimentos', 'Adéntrate en el mundo misterioso de la alquimia medieval en este innovador juego que combina deducción lógica con gestión de recursos. En Alquimistas, los jugadores asumen el papel de investigadores que intentan descubrir las propiedades mágicas de varios ingredientes. El corazón del juego es un ingenioso sistema de deducción: utilizando una aplicación móvil, los jugadores realizan experimentos mezclando ingredientes y observando los resultados. A partir de estos experimentos, deben deducir las propiedades ocultas de cada ingrediente, construyendo una red de conocimiento que les permita crear pociones más poderosas. Pero el conocimiento no es suficiente: también debes gestionar tu reputación, publicar teorías, desacreditar a tus rivales y vender pociones a aventureros incautos. El juego se desarrolla en rondas donde los jugadores deben equilibrar la investigación, la publicación y la obtención de recursos. Con su mezcla única de lógica pura, gestión de recursos y un toque de suerte, Alquimistas ofrece una experiencia de juego desafiante y satisfactoria que recompensa tanto el pensamiento analítico como la astucia táctica.', 59.99, 'Alquimistas.jpg'),
(44, 'Ethnos', 'Juego de control de territorios', 'Sumérgete en un mundo de fantasía donde diversas razas luchan por el control de un reino antiguo en este elegante juego de control de área. En Ethnos, los jugadores compiten para ganar influencia en los diferentes reinos de la tierra de Ethnos reclutando y jugando conjuntos de cartas que representan a diferentes razas fantásticas. Cada raza tiene una habilidad única que se activa cuando juegas un conjunto, añadiendo una capa de estrategia a la construcción de tu mano. El juego se desarrolla en tres eras, con puntuaciones al final de cada una. La tensión aumenta a medida que los jugadores intentan tiempo sus jugadas: jugar conjuntos más grandes te da más influencia, pero esperar demasiado puede significar perder la oportunidad de controlar territorios clave. La mecánica de robar y descartar cartas añade un elemento de gestión de mano y oportunismo. Con su combinación de reglas simples, profundidad estratégica y alta rejugabilidad gracias a la variedad de razas y configuraciones de reino, Ethnos ofrece una experiencia de juego accesible pero desafiante que atrae tanto a jugadores casuales como a estrategas experimentados.', 39.99, 'Ethnos.jpg'),
(45, 'Clank!', 'Juego de construcción de mazos y mazmorras', 'Adéntrate en las profundidades de una peligrosa mazmorra en este emocionante juego que combina la construcción de mazos con la exploración de mazmorras. En Clank!, los jugadores son ladrones audaces que se aventuran en la guarida de un dragón para robar valiosos artefactos. El corazón del juego es un innovador sistema de construcción de mazos: comienzas con un mazo básico y, a medida que avanzas, adquieres nuevas cartas que mejoran tus habilidades de movimiento, combate y adquisición de tesoros. Sin embargo, muchas de estas acciones generan "clank" - ruido que atrae la atención del dragón. Cuanto más ruido hagas, más probabilidades tienes de ser atacado. El tablero de la mazmorra añade un elemento de exploración y riesgo: cuanto más profundo te adentres, más valiosos serán los tesoros, pero también será más difícil escapar. La tensión aumenta a medida que los jugadores intentan equilibrar la codicia con la supervivencia, sabiendo que solo los que logren salir de la mazmorra conservarán sus tesoros. Con su mezcla de estrategia, suerte y un toque de humor, Clank! ofrece una experiencia de juego emocionante y altamente rejugable.', 59.99, 'Clank.jpg'),
(46, 'Keyflower', 'Juego de subastas y desarrollo', 'Embárcate en un viaje de construcción y desarrollo en este profundo juego de gestión de recursos y subastas. En Keyflower, los jugadores compiten para crear el pueblo más próspero a lo largo de cuatro estaciones. El juego combina de manera innovadora mecánicas de subasta, colocación de trabajadores y gestión de recursos. Cada turno, nuevos azulejos de aldea están disponibles para pujar. Los jugadores usan sus trabajadores (representados por meeples) como moneda de puja y como trabajadores para activar las habilidades de los azulejos. La genialidad del juego radica en su sistema de colores: los trabajadores vienen en tres colores, y una vez que se usa un color en una subasta o activación, solo se pueden usar trabajadores de ese color en ese azulejo. Esto crea un fascinante rompecabezas táctico donde debes equilibrar la adquisición de nuevos azulejos, la activación de habilidades y la conservación de trabajadores para futuras pujas. A medida que avanza el año, debes planificar cuidadosamente para asegurarte de tener los recursos necesarios para el invierno. Con su profundidad estratégica, alta interactividad y la tensión constante de las subastas, Keyflower ofrece una experiencia de juego rica y satisfactoria que recompensa la planificación a largo plazo y la adaptabilidad.', 69.99, 'Keyflower.jpg'),
(47, 'Five Tribes', 'Juego de colocación de trabajadores', 'Sumérgete en el exótico mundo de las Mil y Una Noches en este colorido y estratégico juego de colocación de trabajadores. En Five Tribes, los jugadores compiten por el control del mítico Sultanato de Naqala. El juego se desarrolla en un tablero modular compuesto por azulejos que representan diferentes localizaciones del sultanato. La mecánica central es una innovadora variación de la colocación de trabajadores: en lugar de colocar meeples, los jugadores mueven y distribuyen meeples ya presentes en el tablero. Cada una de las cinco tribus (representadas por meeples de diferentes colores) tiene una acción especial que se activa cuando el último meeple de ese color se coloca. Los jugadores deben planificar cuidadosamente sus movimientos para maximizar el efecto de estas acciones, que van desde adquirir recursos y cartas de djinn hasta asesinar a otros meeples o invocar poderosos djinns. El control de los azulejos y la colección de sets de recursos y cartas proporcionan puntos adicionales. Con su combinación de planificación táctica, oportunismo y un toque de cálculo, Five Tribes ofrece una experiencia de juego profunda y satisfactoria que recompensa el pensamiento creativo y la adaptabilidad.', 59.99, 'FiveTribes.jpg'),
(48, 'La Isla Prohibida', 'Juego cooperativo de aventuras', 'Embárcate en una emocionante aventura cooperativa en este accesible juego de estrategia y supervivencia. En La Isla Prohibida, los jugadores forman un equipo de intrépidos aventureros que han llegado a una misteriosa isla en busca de cuatro reliquias sagradas. Sin embargo, la isla está hundiéndose rápidamente, y los jugadores deben trabajar juntos para recuperar las reliquias y escapar antes de que sea demasiado tarde. El juego se desarrolla en un tablero modular compuesto por losetas que representan diferentes partes de la isla. En cada turno, los jugadores deben moverse, realizar acciones para "secar" las losetas inundadas, y recoger cartas de tesoro que les ayudarán en su misión. Después de cada turno, la isla se hunde un poco más, con nuevas losetas inundándose o desapareciendo completamente. La tensión aumenta a medida que el nivel del agua sube y las opciones se reducen. Cada jugador tiene un rol único con habilidades especiales, y la clave del éxito está en utilizar estas habilidades de manera efectiva y coordinada. Con sus reglas simples, componentes atractivos y una dificultad ajustable, La Isla Prohibida ofrece una experiencia de juego emocionante y accesible que fomenta la comunicación y el trabajo en equipo.', 24.99, 'LaIslaProhibida.jpg'),
(49, 'Hanabi', 'Juego cooperativo de fuegos artificiales', 'Participa en un fascinante desafío de comunicación y deducción en este innovador juego de cartas cooperativo. En Hanabi, los jugadores trabajan juntos como maestros pirotécnicos que intentan crear el espectáculo de fuegos artificiales más impresionante. Sin embargo, hay un giro único: puedes ver las cartas de todos los demás jugadores, pero no las tuyas propias. El objetivo es jugar las cartas en el orden correcto para crear cinco series de fuegos artificiales de colores diferentes. En tu turno, puedes dar una pista a otro jugador sobre sus cartas, jugar una carta de tu mano, o descartar una carta para recuperar una ficha de pista. Las pistas están estrictamente limitadas: solo puedes dar información sobre el color o el número de las cartas, nunca ambos. Este sistema de información limitada crea un fascinante rompecabezas de deducción y memoria, donde cada pista debe ser cuidadosamente considerada y recordada. El juego se desarrolla con una tensión creciente, ya que los errores reducen la puntuación final del equipo. Con sus reglas simples pero profundas consecuencias, Hanabi ofrece una experiencia de juego única y desafiante que pone a prueba la comunicación no verbal y la capacidad de inferencia de los jugadores.', 14.99, 'Hanabi.jpg');

INSERT INTO Fotos_Productos (id_producto, foto) VALUES
(6, '7wonders1.jpg'),
(6, '7wonders2.jpg'),
(16, 'agricola1.jpg'),
(16, 'agricola2.jpg'),
(43, 'alquimistas1.jpg'),
(43, 'alquimistas2.jpg'),
(25, 'brassbirmingham1.jpg'),
(25, 'brassbirmingham2.jpg'),
(25, 'brassbirmingham3.jpg'),
(42, 'caverna1.jpg'),
(42, 'caverna2.jpg'),
(45, 'clank1.jpg'),
(45, 'clank2.jpg'),
(14, 'cluedo1.jpg'),
(14, 'cluedo2.jpg'),
(22, 'concordia1.jpg'),
(22, 'concordia2.jpg'),
(22, 'concordia3.jpg'),
(32, 'cosmicencounter1.jpg'),
(32, 'cosmicencounter2.jpg'),
(32, 'cosmicencounter3.jpg'),
(5, 'dixit1.jpg'),
(5, 'dixit2.jpg'),
(13, 'dominion1.jpg'),
(13, 'dominion2.jpg'),
(40, 'elGrande1.jpg'),
(40, 'elGrande2.jpg'),
(44, 'ethnos1.jpg'),
(44, 'ethnos2.jpg'),
(47, 'fiveTribes1.jpg'),
(47, 'fiveTribes2.jpg'),
(26, 'gloomhaven1.jpg'),
(26, 'gloomhaven2.jpg'),
(26, 'gloomhaven3.jpg'),
(49, 'hanabi1.jpg'),
(49, 'hanabi2.jpg'),
(29, 'hive1.jpg'),
(29, 'hive2.jpg'),
(29, 'hive3.jpg'),
(48, 'islaProhibida1.jpg'),
(48, 'islaProhibida2.jpg'),
(46, 'keyflower1.jpg'),
(46, 'keyflower2.jpg'),
(37, 'kingdomino1.jpg'),
(37, 'kingdomino2.jpg'),
(33, 'mageknight1.jpg'),
(33, 'mageknight2.jpg'),
(33, 'mageknight3.jpg'),
(35, 'mansionesDeLaLocura1.jpg'),
(35, 'mansionesDeLaLocura2.jpg'),
(34, 'mechsvsminions1.jpg'),
(34, 'mechsvsminions2.jpg'),
(34, 'mechsvsminions3.jpg'),
(20, 'mysterium1.jpg'),
(20, 'mysterium2.jpg'),
(20, 'mysterium3.jpg'),
(27, 'patchwork1.jpg'),
(27, 'patchwork2.jpg'),
(27, 'patchwork3.jpg'),
(12, 'puertorico1.jpg'),
(12, 'puertorico2.jpg'),
(30, 'sagrada1.jpg'),
(30, 'sagrada2.jpg'),
(30, 'sagrada3.jpg'),
(28, 'santorini1.jpg'),
(28, 'santorini2.jpg'),
(28, 'santorini3.jpg'),
(9, 'scythe1.jpg'),
(9, 'scythe2.jpg'),
(19, 'smallworld1.jpg'),
(19, 'smallworld2.jpg'),
(19, 'smallworld3.jpg'),
(11, 'splendor.jpg'),
(11, 'splendor2.jpg'),
(17, 'stoneage1.jpg'),
(17, 'stoneage2.jpg'),
(17, 'stoneage3.jpg'),
(10, 'terraformingmars!.jpg'),
(10, 'terraformingmars2.jpg'),
(39, 'terraMistica1.jpg'),
(39, 'terraMistica2.jpg'),
(41, 'tigrisYeufrates1.jpg'),
(41, 'tigrisYeufrates2.jpg'),
(21, 'twilightimperium1.jpg'),
(21, 'twilightimperium2.jpg'),
(21, 'twilightimperium3.jpg'),
(31, 'tzolk1.jpg'),
(31, 'tzolk2.jpg'),
(31, 'tzolk3.jpg'),
(23, 'viticulture1.jpg'),
(23, 'viticulture2.jpg'),
(23, 'viticulture3.jpg'),
(24, 'wingspan1.jpg'),
(24, 'wingspan2.jpg'),
(24, 'wingspan3.jpg'),
(36, 'zombicide1.jpg'),
(36, 'zombicide2.jpg'),
(15, 'risk2.jpg'),
(15, 'risk1.jpg'),
(8, 'codenames2.jpg'),
(8, 'codenames1.jpg'),
(7, 'azul2.jpg'),
(7, 'azul1.jpg'),
(4, 'pandemic2.jpg'),
(4, 'pandemic1.jpg'),
(3, 'tickettoride2.jpg'),
(3, 'tickettoride1.jpg'),
(2, 'carcassonne1.jpg'),
(2, 'carcassonne2.jpg'),
(1, 'catan2.jpg'),
(1, 'catan1.jpg'),
(18,'secrethitler1.jpg'),
(18,'secrethitler2.jpg'),
(38,'growingseason1.jpg'),
(38,'growingseason2.jpg');


INSERT INTO Pedidos (id_usuario, fecha_pedido, direccion, estado) VALUES
(1, '2002/11/10', 'No me funciona el insert, Socorro', 'pendiente'),
(2, '2024/11/11', 'Calle Falsa 123', 'enviado'),
(3, '2024/11/12', 'Plaza Central 789, Pueblo Prueba', 'procesando'),
(4, '2024/11/13', 'Calle Secundaria 101, Villa Ejemplo', 'cancelado'),
(5, '2024/11/14', 'Boulevard Estrella 202, Ciudad Real', 'entregado'),
(6, '1492/11/14', 'Imperio azteca, donde Colón desembarcó', 'procesando'),
(7, '2024/11/12', 'Av. del Sol 404, Colonia Modelo', 'pendiente'),
(8, '2024/11/10', 'Calle Horizonte 505, Ciudad Victoria', 'pendiente'),
(9, '2024/11/13', 'Av. Árboles 606, Colonia Verde', 'procesando');

INSERT INTO Detalles_Pedido (id_pedido, id_producto, cantidad, subtotal) VALUES
(1, 1, 2, 79.98),
(1, 5, 1, 29.99),
(2, 2, 1, 29.99),
(2, 10, 2, 139.98),
(3, 7, 3, 104.97),
(3, 15, 1, 44.99),
(4, 6, 2, 79.98),
(5, 8, 1, 19.99),
(5, 12, 1, 44.99),
(6, 3, 1, 44.99),
(7, 14, 2, 49.98),
(8, 9, 1, 79.99),
(9, 4, 1, 39.99),
(9, 16, 1, 44.99);
INSERT INTO Carrito (id_usuario, id_producto, cantidad) VALUES
(1, 1, 2),
(1, 5, 1),
(2, 2, 1),
(2, 10, 2),
(3, 7, 3),
(3, 15, 1),
(4, 6, 2),
(5, 8, 1),
(5, 12, 1),
(6, 3, 1);
