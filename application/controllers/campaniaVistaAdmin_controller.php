<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class CampaniaVistaAdmin_controller extends CI_Controller
{
 public function __construct(){
        parent::__construct();
        $this->load->library('session');
        //echo APPPATH.'libraries\Excel\reader.php';
        require_once(APPPATH.'libraries\Excel\reader.php');
        require_once(APPPATH.'libraries\PHPExcel\Classes\PHPExcel.php');
        
        if($this->session->userdata('logged')==0){
            redirect(base_url().'index.php/login','refresh');
        }
    }

    public function listadoCampanias() {
        $data['listaCampanias'] = $this->campaniaVistaAdmin_model->listarCampaniasActivas();
        $this->load->view('header/header');
        $this->load->view('pages/menu');
        $this->load->view('pages/campanias/campanias_vista_admin', $data);
        $this->load->view('footer/footer');
        $this->load->view('jsview/js_campanias_vista_admin');
    }

    public function detalle_vista_admin($numCampania) {
        $data['clientes'] = $this->campaniaVistaAdmin_model->listarClientes($numCampania);
        $data['campaniaInfo'] = $this->campaniaVistaAdmin_model->campaniaInfo($numCampania);
        
        $this->load->view('header/header');
        $this->load->view('pages/menu');
        $this->load->view('pages/campanias/detallescampVA', $data);
        $this->load->view('footer/footer');
        $this->load->view('jsview/js_campanias_vista_admin');
    }

    public function nuevaCampania() {
        $data['agentes'] = $this->campaniaVistaAdmin_model->listarAgentes();
        $data['ultNoCampania'] = $this->campaniaVistaAdmin_model->ultimoNoCampania();
        $this->load->view('header/header');
        $this->load->view('pages/menu');
        $this->load->view('pages/campanias/nuevaCampania', $data);
        $this->load->view('footer/footer');
        $this->load->view('jsview/js_campanias_vista_admin');
    }

    public function subirExcelCampanias(){
        $numCampania=$this->input->post('codigoCampania');
        $dataCliente=array(); $temp=array();
        $tmp = explode('.', $_FILES['dataExcel']['name']);
        
        $file_extension = end($tmp);

        if ($file_extension=="xlsx") {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            
            $objPHPExcel = $objReader->load($_FILES['dataExcel']['tmp_name']);
            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $arrayData[$worksheet->getTitle()] = $worksheet->toArray();
                for ($i=0; $i < count($worksheet->toArray()); $i++) {
                    if ($i>0) {
                        $dataCliente[$i]['ID_Campannas'] = $numCampania;
                        $dataCliente[$i]['ID_Cliente'] = $worksheet->toArray()[$i][0];
                        $dataCliente[$i]['Meta'] = $worksheet->toArray()[$i][1];
                    }
                }                        
            }
            $this->campaniaVistaAdmin_model->guardarCampaniaCliente($dataCliente, 1);
        }else if ($file_extension=="xls") {
            $data = new Spreadsheet_Excel_Reader();
            $data->setOutputEncoding('CP-1251');
            $data->read($_FILES["dataExcel"]['tmp_name']);
            error_reporting(E_ALL ^ E_NOTICE);

            for ($i=0; $i <= count($data->sheets[0]['cells']); $i++) {

            $Cuenta = (@ereg_replace("[^0-9]", "", $data->sheets[0]['cells'][$i][1]));
                if ($Cuenta<>0){
                    $dataCliente[$i]['ID_Campannas'] = $numCampania;
                    $dataCliente[$i]['ID_Cliente']  = $data->sheets[0]['cells'][$i][1];
                    $dataCliente[$i]['Meta'] = $data->sheets[0]['cells'][$i][2];
                }                
            }
            $this->campaniaVistaAdmin_model->guardarCampaniaCliente($dataCliente, 2);
        }        
    }

    public function guardandoData() {
        $result=$this->campaniaVistaAdmin_model->guardandoNuevaCampania($this->input->post('agentes'),$this->input->post('dataCampania'));
        if ($result) {
            $this->campaniaVistaAdmin_model->incrementarLlave();
        }       
    }

    public function cambiandoEstadoCamp($numCampania, $nuevoEstado) {
        $this->campaniaVistaAdmin_model->actualizandoEstado($numCampania, $nuevoEstado);
    }

    public function graficarMontoReal($idCampania) {
        $this->campaniaVistaAdmin_model->generandoDataGrafica($idCampania);
    }

    public function graficarDiasCampania($idCampania) {
        $this->campaniaVistaAdmin_model->generandoDataGraficaDias($idCampania);
    }

    public function editandoCampania() {
        $this->campaniaVistaAdmin_model->guardandoEdicion($this->input->post('campaniaModificacion'));
    }
}
?>