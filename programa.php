<?php

/**
 * Nombre del alumno: Juliana López Ramírez
 * Maestría: Ingeniería del Software
 * Materia: Computación en el Servidor Web
 * Nombre del profesor: Octavio Aguirre Lozano
 * Descripción: El siguiente código permite implementar la funcionalidad de consulta de los programas sociales
 * que ofrece el área de Desarrollo Integral de la Familia (DIF) de un municipio, además, se puede evaluar para el caso
 * del programa alimentario si el usuario es candidato o no para su registro posterior.
 * Fecha: 06/12/2020
 */

//Se incluye código html mediante el uso de echo para definir el título de la página, así como el formulario
//que se estará utilizando para el envío de los datos mediante el método post
echo "<h1>Desarrollo Integral de la Familia</h1>
<hr />
<form action='programa.php' method='post'>";

//Se evalua si la siguiente variable obtenida mediante el array asociativo $_POST está definida en el formulario y no es nula  
if (isset($_POST['clave_programa'])) {

    //Se recupera el valor informado en la variable clave_programa 
    $clave = $_POST['clave_programa'];

    //Se realiza la consulta del detalle del programa social a partir del parámetro
    //de la clave enviada del formulario, haciendo un llamado a la siguiente función.
    consultarDetallePrograma($clave);
} elseif (isset($_POST['nombre'])) { //Se evalua si la variable 'nombre' obtenida mediante el array asociativo $_POST está definida en el formulario y no es nula 
    //Si la condición se cumple, se recuperará también el valor de las demás variables que se encuentran en ese formulario 
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $edadHijos = $_POST['edad_hijos'];

    //Con los datos recibidos, se hace un llamado a la función evaluarUsuario
    evaluarUsuario($nombre, $edad, $edadHijos);
} else { //Si las condiciones anteriores evaluadas no se cumplieron, significa que aún no se están enviando datos desde formulario
    //y sólo se requiere imprimir el catálogo de programas sociales.
    //En el siguiente arreglo multidimensional se agregan los valores de clave y nombre de programa para cada uno de ellos
    $programas = array(
        array("0001", "Productos de la canasta básica"),
        array("0002", "Programa alimentario"),
        array("0003", "Gestión de aparatos funcionales"),
        array("0004", "Programa de salud visual")
    );

    //Se hace un llamado a la función que imprime en pantalla el catálogo de programas que contiene el array
    consultarProgramas($programas);

    //Se agrega código html para incluir el elemento input en el formulario 
    //para que el usuario capture la clave del programa a consultar
    echo "<br /><p>Introduce la clave del programa para consultar detalles:</p> 
        Clave: <input type='text' name='clave_programa'><br /><br />
        <input type='submit'>
    </form>";
}

/**
 * Función que permite imprimir en pantalla el listado
 * de los programas sociales disponibles, recibiendo como parámetro
 * el array que los contiene.
 */
function consultarProgramas($programas)
{
    //Se obtiene el tamaño del arreglo de los programas
    $tamanioArreglo = sizeof($programas);
    //Se imprime texto descriptivo a la página mediante html
    echo "<b> Programas sociales disponibles </b>";
    //Dado que se trata de un arreglo multidimensaional, se iteran las filas del arreglo de programas y mediante 
    //un for anidado las columnas
    for ($row = 0; $row < $tamanioArreglo; $row++) {
        //Se incluye código html para agregar los elementos que servirán para la presentación
        //de la información en una lista
        echo "<p> ------------------------------------------ </p>";
        echo "<ul>";
        //Iteración de las columnas del arreglo, para este caso el número de columnas es igual a 2. 1 la columna que  contiene
        //la clave y 2 el nombre del programa.
        for ($col = 0; $col < 2; $col++) {
            //Mediante html se agrega un elemento a la lista con los valores recuperados en la iteración actual de fila - columnas
            echo "<li>" . $programas[$row][$col] . "</li>";
        }
        echo "</ul>";
    }
}

/**
 * Función para imprimir en pantalla el detalle del programa social
 * consultado mediante la clave.
 */
function consultarDetallePrograma($clave)
{
    //Mediante la sentencia switch se evalua el valor recibido de la clave
    //para enviar según sea el caso, el texto descriptivo correspondiente al programa.
    switch ($clave) {
        case "0001":
            echo "El programa de la canasta básica consiste en llevar a cada colonia los productos
            que son básicos para la alimentación de las familias. El sistema DIF municipal, ofrece 
            dichos productos a un bajo costo al alcance de las familias de  escasos recursos.
            </form>";
            break;
        case "0002":
            echo "El programa alimentario está enfocado a madres solteras que cuentan con hijos 
            del rango de edades de 1 a 5 años. ";
            echo "<p>Proporciona los siguientes datos para evaluar si cumples el requisito:</p> 
                Nombre: <input type='text' name='nombre'><br /><br />
                Edad: <input type='text' name='edad'><br /><br />
                Edad de los hijos: <input type='text' name='edad_hijos'><br /><br />
                <input type='submit'>
            </form>";
            break;
        case "0003":
            echo "El programa dota de aparatos funcionales a las personas que tienen alguna discapacidad,
            por ejemplo, problemas auditivos. 
            </form>";
            break;
        case "0004":
            echo "El programa de salud visual proporciona lentes gratuitos para las personas
            de escasos recursos o bien, operaciones de cataratas a personas adultos mayores. 
            </form>";
            break;
            //Si la clave recibida no coincidió en ninguno de los casos anteriores, se envía un mensaje 
            //indicando que dicha clave no corresponde a los programas listados
        default:
            echo "La clave enviada no corresponde a los programas sociales disponibles";
    }
}

/**
 * A través de la siguiente función se estará evaluando si el usuario 
 * cumple con los requisitos para inscribirse especificamente en el programa alimentario, 
 * por lo cual se reciben como parámetros los datos capturados en el formulario para ser procesados 
 * y evaluados. El requisito consiste en que las edades de los hijos deben estar en el rango
 * de 1 a 5 años.
 *     
 */
function evaluarUsuario($valorNombre, $valorEdad, $valorEdadHijos)
{
    //Se crea instancia de la clase Usuario, haciendo uso de su método constructor
    //para enviar los valores que se asignarán a los atributos
    $aspirante = new Aspirante($valorNombre, $valorEdad, $valorEdadHijos);

    //Se valida si la cadena recibida lleva ',' (coma) se convierte a un arreglo
    if (strpos($valorEdadHijos, ",") !== false) {
        //Los datos de las edades de los hijos enviados como cadena se convierten en un array
        $edades = explode(",", $valorEdadHijos);
    } else { // si no se cumplió la condición anterior, significaría que solo fue enviado un valor en el campo,
        //por lo tanto solo de define el arreglo con un elemento 
        $edades = [$valorEdadHijos];
    }

    //Se declara contador a utilizar para validar cuántos de los hijos cumplen el requisito de edad
    $count = 0;

    //Se itera el arreglo de edades de los hijos para validar si se encuentran dentro del rango permitido
    //para ser beneficiarios del programa.
    foreach ($edades as $valor) {
        //Se valida si el valor de la iteración actual cumple con el rango de 1 a 5
        if ($valor >= 1 && $valor <= 5)
            //Se incrementa el contador si la condición se cumple
            $count++;
    }

    //Se declara variable auxiliar para evaluar posteriormente si se hará el llamado a otra función
    $cumple = false;
    //Se muestra mensaje personalizado al usuario con su nombre en mayúsculas, el método get de la clase Usuario
    //tiene definido realizar la conversión de la cadena para retornarlo en ese formato
    echo '<p> Estimada ' . $aspirante->getNombre() . '</p>';
    //Se valida si el contador fue igual a 1 para enviar a imprimir un mensaje personalizado
    if ($count === 1) {
        echo '<p > Uno de sus hijos es acreedor al programa </p>';
        //Se asigna valor en true a la variable auxiliar que indica que será requerido el llamado a otra función
        //porque se cumplió la condición
        $cumple = true;
    }
    //Se valida si el contador fue mayor que cero y diferente de 1 para personalizar
    //el mensaje a imprimir de acuerdo al total del contador
    elseif ($count > 0 && $count != 1) {
        echo '<p>' . $count . ' de sus hijos son acreedores al programa </p>';
        //Se asigna valor en true a la variable auxiliar que indica que será requerido el llamado a otra función
        //porque se cumplió la condición
        $cumple = true;
    } else //Si no se cumplieron las condiciones anteriores, significa que no cumple los requisitos el usuario y se le notifica
        //mediante la impresión del mensaje
        echo ' Lo sentimos, no cubre los requisitos para el programa.';


    //Si se cumplieron las condiciones para ser beneficiario del programa,  la variable auxiliar 'cumple' debe ser true, por lo tanto
    //se evalua su valor
    if ($cumple == true)
        //Se hace llamado a la función consultaDias para informar al usuaario que cumplió el requisito, de los días en qué deberá
        //presentar la documentación para inscribirse
        consultarDias();
}

/**
 * Función para mostrar los días en que se puede entregar
 * la documentación del registro en el programa social
 */
function consultarDias()
{
    //Arreglo que contiene los dias de la semana en los cuales se puede realizar el registro al programa social
    $dias = ["Lunes", "Miércoles", "Viernes"];
    //Contador a  utilizar para el while que recorrerá el arreglo de los días de entrega de documentos
    $countDias = 0;

    //Se imprime mensaje y se agregan elementos html. La iteración se realizará una vez, puesto que al 
    //llegar a la evaluación de la condición ésta no se cumplirá y el bucle terminará.
    do {
        echo '<p> Si deseas inscribirte, puedes entregar tus documentos los siguientes días: </p>';
        echo '<ul>';
        //Se itera el arreglo de días
    } while ($countDias > count($dias));

    //Con el siguiente ciclo se imprimen los dias contenidos en el arreglo 
    while ($countDias < count($dias)) {
        //Se imprime el valor del arreglo contenido en la posición que indica el contador
        echo '<li>' . $dias[$countDias] . '</li>';
        //se incrementa contador
        $countDias++;
    }
    //Cierre de la etiqueta de la lista de días
    echo '</ul>';
}


/**
 * Clase que define los atributos y métodos correspondientes del usuario
 * que es evaluado para ser beneficiario de algún programa social.
 */
class Aspirante
{
    //Declaración de atributos 
    protected $nombre, $edad, $embarazada, $edadHijos;

    //Se define método constructor de la clase con parámetros
    function __construct($nombre, $edad, $edadHijos)
    {
        //Se asignan los datos a las variables miembro
        //haciendo referencia al objeto actual
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->edadHijos = $edadHijos;
    }
    //Función set para asignarle valor al atributo nombre
    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    //Función set para asignarle valor al atributo edad
    function setEdad($edad)
    {
        $this->edad = $edad;
    }

    //Función set para asignarle valor al atributo edadHijos
    function setEdadHijos($edadHijos)
    {
        $this->edadHijos = $edadHijos;
    }
    //Función get para obtener el valor del atributo nombre    
    function getNombre()
    {
        //Se utiliza la función strtoupper para
        //retornar el valor en mayúsculas
        return strtoupper($this->nombre);
    }

    //Función get para obtener el valor del atributo edad
    function getEdad()
    {
        return $this->edad;
    }

    //Función get para obtener el valor del atributo edadHijos
    function getEdadHijos()
    {
        return $this->edadHijos;
    }
}
