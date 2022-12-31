<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
// require_once FCPATH . 'vendor/autoload.php';

ini_set('max_execution_time', 0);
ini_set('memory_limit', '30720M');

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Excelimport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ExcelModel');
        $this->load->helper(array('form', 'url'));

    }

    public function index()
    {
        $this->load->view('index');
    }

    public function spreadhseet_format_download()
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="hello_world.xlsx"');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'S.No');
        $sheet->setCellValue('B1', 'Product Name');
        $sheet->setCellValue('C1', 'Quantity');
        $sheet->setCellValue('D1', 'Price');

        $writer = new Xlsx($spreadsheet);
        $writer->save("php://output");

    }

    public function spreadsheet_import()
    {

//code to upload file on upload folder

        $temp_name = $this->input->post('sample');
        $party_explode = explode('|', $temp_name);
        $id = $party_explode[0];
        // echo "id: " . $party_explode[0] . "<br />";
        // echo "no of columns: " . $party_explode[1] . "<br />";
        // echo "name: " . $party_explode[2] . "<br />";
        $filename = $_FILES["file"]["name"];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xls|csv|txt|xlsx';
        // $config['encrypt_name'] = true;
// $config['max_size'] = 100;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            echo $error['error'];

        } else {
            $data = array('upload_data' => $this->upload->data());
            // echo "file Uploaded succeefully";

        }

        $filePath = 'C:\xampp\htdocs\webadmin\uploads/' . $_FILES['file']['name'];
        $newfilepath = str_replace("\\", "/", $filePath);
        // echo $newfilepath;
        $upload_file = $_FILES['file']['name'];
        $extension = pathinfo($upload_file, PATHINFO_EXTENSION);
        if ($extension == 'csv') {
            $reader = ReaderEntityFactory::createReaderFromFile($_FILES['file']['name']);
        }
        // else if ($extension == 'xlsx') {
        //     $reader = ReaderEntityFactory::createXLSXReader();
        // }
        else {
            $this->session->set_flashdata('result', 0);
            redirect("welcome/index", 'refresh');
        }
        $reader->setShouldFormatDates(true);

        $reader->open($_FILES['file']['tmp_name']);

//get columns size
        $columnsize = null;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if ($rowIndex == 1) {
                    $columnsize = count($row->getCells());
                    $columnname = $row->toArray();
                } else {
                    break;
                }
            }
        }

        if ($columnsize != $party_explode[1]) {
            $this->session->set_flashdata('result', 1);
            redirect("welcome/index", 'refresh');

        } else {

            // echo $columnsize;

            // var_dump($columnname);

            $inserdata = $this->ExcelModel->createTable($id);

            $datatype = $this->ExcelModel->template_structure();

            $reader->close();
            $tables_columns = ['created_by' => $this->session->userdata('user_id'), 'created_date' => mdate('%Y-%m-%d'), 'updated_by' => $this->session->userdata('user_id'), 'updated_date' => mdate('%Y-%m-%d %H:%i:%s')];
            $this->ExcelModel->load_file($newfilepath, $tables_columns);
            $table_data = $this->ExcelModel->fetchData();
            echo '<pre>';
            print_r($table_data);
            echo '</pre>';

            // if ($columnsize != $party_explode[1]) {
            //     echo "file sample mistamatch";die;
            // } else {
            //     foreach ($reader->getSheetIterator() as $sheet) {
            //         foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            //             if ($rowIndex !== 1) {
            //                 $cells = $row->toArray();
            //                 $data = array_combine($columnname, $cells);
            //                 $data += ['created_by' => $this->session->userdata('user_id'), 'created_date' => mdate('%Y-%m-%d'), 'updated_by' => $this->session->userdata('user_id'), 'updated_date' => mdate('%Y-%m-%d %H:%i:%s', now())];
            //                 // $this->ExcelModel->insert_excel($data);
            //                 echo '<pre>';
            //                 print_r($data);
            //                 echo '</pre>';
            //                 // $this->ExcelModel->insert_excel($cells);
            //             }
            //         }
            //     }
            // }

            // echo 'loaded';
        }
    }

//functions to check data type

    public function validate_int($val)
    {
        if (filter_var($val, FILTER_VALIDATE_INT) !== false) {
            echo ("Variable is an integer") . " ";
            // return var_dump(true);
        } else {
            // return var_dump(false);

        }
    }

    public function validate_date($val)
    {
        $res = strtotime($val);
        if ($res) {
            echo "this is valid date" . " ";
        } else {
            echo "invalid date";
        }
    }

    public function validate_string($val)
    {
        if (is_string($val)) {
            echo ("data is a string") . " ";
            // return var_dump(true);
        } else {
            // return var_dump(false);
            echo ("data is not a string") . " ";

        }
    }

    public function validate_data_float($val)
    {
        $float_value = (float) $val;
        if (strval($float_value) == $val) {
            // return var_dump(true);
            echo 1;

            // echo "this is a float data" . ' ';
        } else {
            // echo "this is not a float data";
            echo 0;

        }

    }

    public function spreadsheet_export()
    {
        //fetch my data
        $productlist = $this->excelmodel->product_list();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="product.xlsx"');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'S.No');
        $sheet->setCellValue('B1', 'Product Name');
        $sheet->setCellValue('C1', 'Quantity');
        $sheet->setCellValue('D1', 'Price');
        $sheet->setCellValue('E1', 'Subtotal');

        $sn = 2;
        foreach ($productlist as $prod) {
            //echo $prod->product_name;
            $sheet->setCellValue('A' . $sn, $prod->product_id);
            $sheet->setCellValue('B' . $sn, $prod->product_name);
            $sheet->setCellValue('C' . $sn, $prod->product_quantity);
            $sheet->setCellValue('D' . $sn, $prod->product_price);
            $sheet->setCellValue('E' . $sn, '=C' . $sn . '*D' . $sn);
            $sn++;
        }
        //TOTAL
        $sheet->setCellValue('D8', 'Total');
        $sheet->setCellValue('E8', '=SUM(E2:E' . ($sn - 1) . ')');

        $writer = new Xlsx($spreadsheet);
        $writer->save("php://output");
    }

    public function Validate($bank_data)
    {

//fetching columns datatype

        // $datatype = $this->ExcelModel->template_structure();
        // foreach ($datatype as $columndatatype) {
        //     echo '<pre>';
        //     echo $columndatatype['col_name'] . ':' . $columndatatype['col_datatype'];
        //     echo '</pre>';
        // }

        //fetch data from database
        $this->validate_int('2');
        $this->validate_date('15--2021');
        $this->validate_data_float('100000.12213');
        $this->validate_string(1);

        $count = count($bank_data);
        $counter = 1;
        echo '<table style="border:1px solid black; width:100%;" class="table">';
        echo '<tr>';

        foreach ($bank_data as $key => $value) {
            foreach ($value as $subkey => $subvalue) {
                echo '<th>' . $subkey . '</th>';
            }
            if ($counter == 1) {
                break;
            }
            $counter++;
        }

        echo '</tr>';
        $datatype = array();
        // foreach ($bank_data as $key => $value) {
        //     echo '<tr>';
        //     foreach ($value as $subkey => $subvalue) {
        //         $datatype = array(
        //             'id' => 'validate_float',
        //             'temp_id' => 'validate_float',
        //             'template_name' => 'validate_float',
        //             'type' => 'validate_float',
        //             'a' => 'validate_float',
        //             'b' => 'validate_float',
        //             'c' => 'validate_float',
        //             'd' => 'validate_float',
        //             'e' => 'validate_float',
        //             'f' => 'validate_float',
        //             'created_date' => 'validate_float',
        //             'last_access_date' => 'validate_float',
        //             'user_id' => 'validate_float',
        //         );
        //     }
        //     echo '</tr>';

        // }
        foreach ($bank_data as $key => $value) {
            echo '<tr>';

            foreach ($value as $subkey => $subvalue) {
                // echo '<th>' . $subkey . '</th>';
                echo '<td>' . $bank_data[$key][$subkey];
                // echo $this->validate_date($bank_data[$key][$subkey]);'</td>';
            }

            echo '</tr>';
        }
        echo '</table>';

        // echo '<pre>';
        // print_r($bank_data);
        // echo '</pre>';

        //iterrate result with condition to validate each cells

    }

}