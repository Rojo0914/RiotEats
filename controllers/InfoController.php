<?php

class InfoController {
    private function render($titulo, $subtitulo, $secciones) {
        $view = "views/info/pagina.php";
        include_once("views/main.php");
    }

    public function prensa() {
        $this->render(
            "Sala de Prensa",
            "Recursos oficiales, notas de lanzamiento y kit de medios de Riot Eats",
            [
                ["titulo" => "Comunicados Oficiales 2026", "texto" => "Riot Eats inaugura su nueva línea de hamburguesas inspiradas en la Grieta y Piltover, combinando cocina smash artesanal con entregas ultrarrápidas en toda el área metropolitana."],
                ["titulo" => "Kit de Prensa y Logotipos", "texto" => "Los medios de comunicación y creadores de contenido pueden solicitar nuestro dossier de marca, fotografías en alta resolución y logotipos vectoriales escribiendo a prensa@rioteats.es."],
                ["titulo" => "Contacto para Medios", "texto" => "Para entrevistas, colaboraciones en eventos de esports o acreditaciones, contacta con nuestro departamento de comunicación (respuesta habitual en menos de 24 horas laborables)."]
            ]
        );
    }

    public function seguridad() {
        $this->render(
            "Seguridad Alimentaria y Digital",
            "Nuestros estándares en cocina, reparto y protección de cuentas",
            [
                ["titulo" => "Trazabilidad y Control en Cocina", "texto" => "Todos nuestros ingredientes pasan controles diarios de temperatura y frescura. Trabajamos con proveedores certificados y protocolos estrictos de manipulación y prevención de alérgenos."],
                ["titulo" => "Seguridad de tu Cuenta y Pagos", "texto" => "Tus credenciales se almacenan cifradas mediante algoritmos seguros y todas las transacciones se procesan bajo estándares PCI-DSS sin almacenar datos bancarios en nuestros servidores."],
                ["titulo" => "Reporte de Vulnerabilidades", "texto" => "Si detectas cualquier fallo técnico o de seguridad en nuestra plataforma web, notifícalo a seguridad@rioteats.es para que nuestro equipo lo revise de inmediato."]
            ]
        );
    }

    public function legal() {
        $this->render(
            "Aviso Legal",
            "Información corporativa y condiciones generales de titularidad",
            [
                ["titulo" => "Titularidad del Sitio Web", "texto" => "En cumplimiento con la normativa vigente, se informa que este sitio web es operado por Riot Eats, Inc., con domicilio social en Avenida de la Grieta 42, Barcelona, España."],
                ["titulo" => "Propiedad Intelectual e Industrial", "texto" => "El diseño gráfico, código fuente, logotipos, fotografías de productos y nombres comerciales de Riot Eats están protegidos por las leyes de propiedad intelectual. Queda prohibida su reproducción sin autorización."],
                ["titulo" => "Responsabilidad de Uso", "texto" => "Riot Eats se reserva el derecho a actualizar los contenidos, precios y disponibilidad de la carta sin previo aviso para garantizar la calidad del servicio."]
            ]
        );
    }

    public function privacidad() {
        $this->render(
            "Aviso de Privacidad",
            "Cómo recopilamos, usamos y protegemos tus datos personales",
            [
                ["titulo" => "Datos que Recopilamos", "texto" => "Únicamente solicitamos los datos necesarios para gestionar tu cuenta, tramitar tus pedidos y realizar la entrega: nombre, correo electrónico, dirección de envío y teléfono de contacto."],
                ["titulo" => "Finalidad del Tratamiento", "texto" => "Tus datos se emplean exclusivamente para procesar pedidos, gestionar promociones activas y ofrecer soporte postventa. Nunca vendemos ni cedemos tu información a terceros con fines publicitarios."],
                ["titulo" => "Tus Derechos (RGPD)", "texto" => "Puedes acceder, rectificar o solicitar la eliminación definitiva de tu cuenta y datos personales en cualquier momento desde tu perfil o escribiendo a privacidad@rioteats.es."]
            ]
        );
    }

    public function terminos() {
        $this->render(
            "Términos de Servicio",
            "Condiciones aplicables a los pedidos y uso de la plataforma Riot Eats",
            [
                ["titulo" => "Realización de Pedidos", "texto" => "Al confirmar un pedido en Riot Eats, el usuario acepta el importe total detallado, incluyendo los extras seleccionados y los gastos de gestión aplicables."],
                ["titulo" => "Tiempos de Entrega y Cancelaciones", "texto" => "El tiempo estimado de preparación y reparto es de 25 a 40 minutos. Una vez que el pedido entra en fase de cocina, no podrá cancelarse salvo incidencia justificada."],
                ["titulo" => "Promociones y Códigos", "texto" => "Los cupones promocionales no son acumulables entre sí y están sujetos a disponibilidad de stock y fechas de validez indicadas en la sección Promociones."]
            ]
        );
    }

    public function asistencia() {
        $this->render(
            "Asistencia al Cliente",
            "¿Problemas con tu pedido? Estamos aquí para ayudarte",
            [
                ["titulo" => "Incidencias con Pedidos en Curso", "texto" => "Si tu pedido llega incompleto, frío o con algún error en los extras, contáctanos dentro de las primeras 2 horas tras la entrega para enviarte una reposición o reembolso inmediato."],
                ["titulo" => "Horario de Atención", "texto" => "Nuestro equipo de soporte atiende de lunes a domingo de 12:00 a 00:00 h a través del formulario de Contáctanos o en el teléfono gratuito 900 123 456."],
                ["titulo" => "Preguntas Frecuentes", "texto" => "Puedes consultar el estado de tus pedidos anteriores desde tu perfil de usuario y modificar tus ingredientes extra antes de finalizar la compra en el carrito."]
            ]
        );
    }

    public function verificar() {
        $this->render(
            "Verificar en Línea",
            "Comprobación de autenticidad de pedidos, facturas y códigos promocionales",
            [
                ["titulo" => "Verificación de Pedidos y Facturas", "texto" => "Cada pedido realizado en Riot Eats genera un identificador único asociado a tu sesión y correo electrónico, válido como comprobante oficial de compra."],
                ["titulo" => "Autenticidad de Códigos Promocionales", "texto" => "Los códigos oficiales de Riot Eats únicamente se publican en nuestra web oficial y en nuestros perfiles verificados de redes sociales. Desconfía de generadores externos."],
                ["titulo" => "Estado del Servicio", "texto" => "Todos nuestros sistemas de pedidos, pasarela de pago y cocina central operan actualmente con normalidad (99.9 % de disponibilidad)."]
            ]
        );
    }

    public function accesibilidad() {
        $this->render(
            "Declaración de Accesibilidad",
            "Compromiso con una experiencia web inclusiva para todos los usuarios",
            [
                ["titulo" => "Estándares Web (WCAG 2.1)", "texto" => "Riot Eats trabaja continuamente para cumplir con las pautas de accesibilidad AA, garantizando contraste adecuado entre texto y fondo, etiquetas descriptivas en imágenes y navegación clara."],
                ["titulo" => "Navegación por Teclado y Lectores", "texto" => "Todos los controles interactivos, formularios de pedido y selectores de extras están estructurados semánticamente para ser compatibles con lectores de pantalla."],
                ["titulo" => "Sugerencias de Mejora", "texto" => "Si encuentras alguna barrera de acceso en nuestra plataforma, infórmanos a accesibilidad@rioteats.es para corregirla en próximas actualizaciones."]
            ]
        );
    }

    public function informes() {
        $this->render(
            "Informes Anuales",
            "Transparencia, crecimiento y sostenibilidad de Riot Eats",
            [
                ["titulo" => "Memoria de Actividad 2025 - 2026", "texto" => "Durante el último ejercicio, Riot Eats superó los 120.000 pedidos entregados, ampliando su carta con opciones vegetarianas y reduciendo en un 18 % los tiempos medios de reparto."],
                ["titulo" => "Compromiso con Envases Sostenibles", "texto" => "El 95 % de nuestro packaging es reciclable y libre de plásticos de un solo uso, utilizando cartón certificado FSC para todas nuestras cajas de hamburguesas y complementos."],
                ["titulo" => "Impacto Local", "texto" => "Más del 80 % de nuestros productos frescos y panadería artesanal provienen de productores locales situados a menos de 100 km de nuestras cocinas."]
            ]
        );
    }

    public function pacto() {
        $this->render(
            "Pacto de la Clientela",
            "Nuestro compromiso de calidad, respeto y juego limpio contigo",
            [
                ["titulo" => "1. Ingredientes Reales, Sin Atajos", "texto" => "Cocinamos cada hamburguesa al momento tras recibir tu pedido. Nunca recalentamos piezas ni reducimos raciones."],
                ["titulo" => "2. Precios Claros y Sin Sorpresas", "texto" => "El precio que ves desde el primer clic —con tus extras sumados al instante— es el precio final que pagas, sin comisiones ocultas de última hora."],
                ["titulo" => "3. Respeto en la Comunidad", "texto" => "Tanto en nuestros locales como en el trato con repartidores y personal de cocina, fomentamos un entorno seguro, cordial y libre de cualquier tipo de discriminación."]
            ]
        );
    }
}