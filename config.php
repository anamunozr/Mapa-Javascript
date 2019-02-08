<?php

//DB
$server = "";
if(isset($_SERVER['HTTP_HOST'])){
    $server = $_SERVER['HTTP_HOST'];
}

switch($server){
	
	case "localhost":{
		$DB_HOST="localhost";
        $DB_USER="movform";
        $DB_PASS="m1234";
        $DB_NAME="gestiongps";
        $FILES_REPORT = "/srv/mf4desa.movilform.com/files_report/";
        $URL = "http://mf4desa.movilform.com/uploads/";
        $ENV = "MF4DESA";
        $BASEDIR = "/srv/mf4desa.movilform.com/";
		$DIRECTORY = "mf4desa.movilform.com/";
        $PROCESS_AUTH = "mf4-desa-1997";
        break;
    }



    default:{
        $DB_HOST="";
        $DB_USER="";
        $DB_PASS="";
        $DB_NAME="";
        $FILES_REPORT = "/srv/desa4.movilform.com/files_report/";
        $URL = "http://desa4.movilform.com/uploads/";
        $ENV = "MF4DESA4";
        $BASEDIR = "/srv/desa4.movilform.com/";
		$DIRECTORY  = "desa4.movilform.com/";
        $PROCESS_AUTH = "mf4-desa-1997";
        break;
    }



}

//Carpeta donde se almacenan los reportes excel
$VAR_REPORT_FOLDER= "files_report";

$results_by_page = 20;

//Ruta de servidor Router
$ROUTER_SERVER = array(
   // "router_prod_update_imei"   =>"http://routerdesa.movilform.com/rest/router/imei/update",
   // "router_prod_add_imei"      =>"http://routerdesa.movilform.com/rest/router/imei/profile",
    "router_prod_update_imei"   =>"http://router.movilform.com/rest/router/imei/update",
    "router_prod_add_imei"      =>"http://router.movilform.com/rest/router/imei/profile"
);

$DEF_CONFIG = array(
    "app_logo"          =>"logo_movilform_small.jpg",
    "app_title"         =>"MovilForm",
    "app_environment"   =>"Prod",
    "app_version"       =>"4.0 (prod)",
    "app_timezone"      =>"-03:00",
    "app_gcm_api_key"   => "AIzaSyDRXU7VKXl6kkd9PuaUOi3931bX5Wt-K8A",
    "app_gcm_url"       => 'https://android.googleapis.com/gcm/send',
    "app_mobile_current_ver" =>'4.2.6',
    "app_default_section"   =>"#/routes/status",
    "routes_label"          =>array("ruta","rutas"),
    "tasks_label"           =>array("tarea","tareas"),
    "companies_label"       =>array("empresa","empresas"),
    "contracts_label"       =>array("contrato","contratos"),
    "customers_label"       =>array("cliente","clientes"),
    "divisions_label"       =>array("departamento","departamentos"),
    "goods_label"           =>array("artículo","artículos"),
    "services_label"        =>array("servicio","servicios"),
    "vehicles_label"        =>array("vehículo","vehículos"),
    "workers_label"         =>array("trabajador","trabajadores"),
    "devices_label"         =>array("dipositivo","dispositivos"),

    "status_done"=>"DONE",
    "status_doing"=>"DOING",
    "status_todo"=>"TODO",

    "message_update_assignment"=>"Se han actualizado sus tareas, se requiere que actualice datos.",

    "contracts_with_all_services"=>FALSE,
    "contracts_with_goods"=>FALSE,
    "contracts_with_time"=>TRUE,
    "contracts_with_comment"=>TRUE
);


$FIELDS_LOAD_ROUTES = array(
    //    Nombre de campo     ,Obligatorio, Descripcion, Nombre campo en db
    array("TRABAJADOR"			,true ,"Nombre completo de trabajador"		,"work_name"),
    array("VEHICULO"			,false,"Identificador de vehículo"			,"vehi_code"),
    array("RUTA_NOMBRE"			,true ,"Nombre de ruta"						,"assi_name"),
    array("RUTA_FECHA_INICIO"	,true ,"Fecha inicio ruta (formato yyyy-mm-dd)","assi_date_assignment_start"),
    array("RUTA_FECHA_TERMINO"	,true ,"Fecha término ruta (formato yyyy-mm-dd)","assi_date_assignment_end"),
    array("RUTA_CODIGO"			,false,"Código de ruta"						,"assi_code"),
    array("CONTRATO_CODIGO"		,true ,"Código de contrato"					,"cont_code"),
    array("SERVICIO_NOMBRE"		,true ,"Nombre de servicio"					,"serv_name"),
    array("SERVICIO_CANTIDAD"	,false,"Cantidad de servicios a realizar"	,"asst_goods"),
    array("SERVICIO_HORA"		,false,"Hora a realizar servicio"			,"asst_time"),
    array("SERVICIO_COMENTARIO"	,false,"Comentario sobre servicio"			,"asst_comment"),
    array("SERVICIO_CODIGO"		,false,"Código de servicio"					,"asst_code")
);

$LOGS_CONFIG = array(
    'rootLogger' => array(
        //'level' => "ERROR",
        'appenders' => array('default'),
    ),
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%date{d.m.Y H:i:s,u} [%-5level] %msg%n'
                )
            ),
            'params' => array(
                'file' => $BASEDIR.'logs/movilform4_%s.log',
                'datePattern' => 'Ymd'
            )
        )
    )
);
