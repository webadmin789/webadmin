<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Excelmodel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function insert_batch($data)
    {
        $this->db->insert_batch('bank_data', $data);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function createTempTable($data)
    {
        $column_name = [];
        $sql = 'CREATE TEMPORARY TABLE temp_bank_data (id INT NOT NULL AUTO_INCREMENT, ';
        $count = 1;
        foreach ($data as $key => $value) {
            foreach ($value as $subkey => $subvalue) {
                $sql .= ' ' . $subkey . ' VARCHAR( 40 ),';
            }
            if ($count == 1) {
                break;
            }
            $count++;
            // $sql .= ' ' . $column_name[$key] . ' VARCHAR( 40 ),';
        }

        $sql .= 'PRIMARY KEY ( `id` )) ENGINE = InnoDB;';
        echo $sql;
        echo '<br>';
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        $this->db->query("DROP TABLE IF EXISTS temp_bank_data");
        $res = $this->db->query($sql);
        $this->db->insert_batch('temp_bank_data', $data);
        if ($this->db->affected_rows() > 0) {
            $query = $this->db->get('temp_bank_data')->result_array();
            return $query;
        } else {
            return 0;
        }

        // $this->db->insert_batch('temp_bank_data', $data);
        // if ($this->db->affected_rows() > 0) {
        //     return $query = $this->db->get('temp_bank_data')->result_array();
        // } else {
        //     return 0;
        // }
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    }

//function to create mysql table
    public function createTable($data)
    {
        $datatype = $this->template_structure();
        // echo '<pre>';
        // print_r($datatype);
        // echo '</pre>';

        $count = 1;
        $column_name = [10];
        $sql = 'CREATE TABLE temp_excel_table (id INT NOT NULL AUTO_INCREMENT, ';
        foreach ($datatype as $value) {
            if ($value['col_datatype'] == 'int') {
                $sql .= ' ' . $value['col_name'] . ' ' . $value['col_datatype'] . ' ' . '(20)' . ',';
            } else if ($value['col_datatype'] == 'varchar') {
                $sql .= ' ' . $value['col_name'] . ' ' . $value['col_datatype'] . '(111)' . ',';
            } else {
                $sql .= ' ' . $value['col_name'] . ' ' . $value['col_datatype'] . ',';
            }
        }

        $sql .= 'created_by int(10), created_date date,updated_by int(10),updated_date datetime ,PRIMARY KEY ( `id` )) ENGINE = InnoDB;';
        // echo $sql;
        // echo '<br>';

        $this->db->query("DROP TABLE IF EXISTS temp_excel_table");
        $res = $this->db->query($sql);
        if (!$res) {
            $this->session->set_flashdata('result', 4);
            // redirect("welcome/index", 'refresh');
        }
        // $this->db->insert_batch('temp_bank_data', $data);
        // if ($this->db->affected_rows() > 0) {
        //     return $query = $this->db->get('temp_bank_data')->result_array();
        // } else {
        //     return 0;
        // }
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    }

//fetching template structure(columns) datatype
    public function template_structure()
    {
        $result = $this->db->get('temp_structure')->result_array();
        return $result;
    }

    // uploading excel data to mysql table
    public function load_file($filepath, $data)
    {
        $datatype = $this->template_structure();
        $count = count($datatype);
        $counter = 1;
        $counter2 = 1;
        // echo $count;

        $query = "LOAD DATA INFILE '" . $filepath . "' INTO TABLE temp_excel_table
   FIELDS TERMINATED BY ','
   LINES TERMINATED BY '\\n'
   IGNORE 1 LINES
   (
   ";

        foreach ($datatype as $table) {
            if ($table['col_datatype'] == 'date') {
                if ($counter == $count) {
                    $query .= '@' . $table['col_name'];
                } else {
                    $query .= '@' . $table['col_name'] . ',';
                }

            } else {
                if ($counter == $count) {
                    $query .= $table['col_name'];
                } else {
                    $query .= $table['col_name'] . ',';
                }

            }
            $counter++;
        }
        $query .= ") SET ";

        foreach ($datatype as $date_columns) {
            if ($date_columns['col_datatype'] == 'date') {
                $query .= $date_columns['col_name'] . "= STR_TO_DATE(@" . $date_columns['col_name'] . ", '%m/%d/%y'),";
            }

        }

        foreach ($data as $key => $value) {
            if ($counter2 == count($data)) {
                $query .= $key . '=' . "'" . $value . "'" . ';';
            } else {
                $query .= $key . '=' . "'" . $value . "'" . ',';
            }
            $counter2++;
        }

        // echo $query;
        $res = $this->db->query($query);

        if ($res) {
            echo "data uploaded successfully";
            $this->session->set_flashdata('result', 2);
            redirect("welcome/index", 'refresh');

        } else {
            echo 'Something went wrong';
            $this->session->set_flashdata('result', 3);
            redirect("welcome/index", 'refresh');
        }

    }

    public function insert_excel($data)
    {
        $res = $this->db->insert('temp_excel_table', $data);
        echo $this->db->last_query();

        if ($res) {
            echo "record inserted";
        } else {
            echo "record not inserted";
        }

    }

    public function party_list()
    {
        $this->db->select('*');
        $this->db->from('m_party');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function fetchData()
    {
        $column = $this->db->query('SELECT count(*) as columns FROM information_schema.columns WHERE table_name ="bank_data"');
        $column_name = $this->db->query('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "webadmin" AND TABLE_NAME = "bank_data"');
        $query = $this->db->get('temp_excel_table');
        $data = $query->result_array();
        // var_dump(data);die;
        return $data;

    }
}