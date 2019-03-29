<?php

namespace reward\models;

use Yii;

class PayrollResultOci extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db_o;

    }

//
//        header('Access-Control-Allow-Origin: *');
//
//        $tns = "
//
//(DESCRIPTION =
//
//    (ADDRESS_LIST =
//
//      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.10.8.11)(PORT = 1521))
//
//    )
//
//    (CONNECT_DATA =
//
//      (SID = PRODHR)
//
//    )
//
//  )
//
//       ";
//
//        try {
//
//            $conn = new PDO("oci:dbname=" . $tns, 'b2b1', 'passw0rd');
//
//            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//            echo 'SUCCESS: ';
//
//        } catch (PDOException $e) {
//
//            echo 'ERROR: ' . $e->getMessage();
//
//        }
//
//}

    public static function getData()
    {
        $currentDate = date("m-Y");

        $query = Yii::$app->db_o->createCommand("SELECT 
        --pap.PAYROLL_NAME
         TO_CHAR(TO_DATE(ppa.effective_date,'DD-MM-RRRR'),'RRRRMM') PERIOD
         ,pet.element_name ELEMENT_NAME
         ,SUM(prv.result_value) CURR_AMOUNT
            
        FROM apps.pay_payroll_actions ppa

        JOIN apps.pay_assignment_actions pac ON ppa.payroll_action_id = pac.payroll_action_id
        JOIN apps.pay_run_results prr ON pac.assignment_action_id = prr.assignment_action_id
        JOIN apps.pay_run_result_values prv ON prr.run_result_id = prv.run_result_id
        JOIN apps.pay_input_values_f piv ON piv.input_value_id = prv.input_value_id
        JOIN apps.pay_element_types_f pet ON pet.element_type_id = piv.element_type_id
        JOIN apps.pay_element_classifications pec ON pec.classification_id = pet.classification_id
        JOIN apps.per_all_assignments_f pas ON pas.assignment_id = pac.assignment_id
        JOIN apps.per_all_people_f per ON pas.person_id = per.person_id
        JOIN apps.pay_all_payrolls_f pap ON pas.payroll_id = pap.payroll_id
        JOIN apps.hr_soft_coding_keyflex sck ON pas.soft_coding_keyflex_id = sck.soft_coding_keyflex_id
            
        WHERE ppa.action_type IN ('R','Q','B')
        AND TO_CHAR(TO_DATE(ppa.effective_date,'DD-MM-RRRR'),'RRRRMM') = TO_CHAR(TO_DATE('$currentDate','MM-RRRR'), 'RRRRMM')
        AND (ppa.date_earned BETWEEN pet.effective_start_date AND pet.effective_end_date)
        AND (ppa.effective_date BETWEEN pas.effective_start_date AND pas.effective_end_date)
        AND (ppa.effective_date BETWEEN per.effective_start_date AND per.effective_end_date)
        AND (prv.result_value IS NOT NULL AND prv.result_value > '1' AND prv.result_value != 'N' AND prv.input_value_id != '1296' AND prv.input_value_id != '513')
        AND ppa.action_status = 'C'
        AND UPPER(piv.NAME) = 'PAY VALUE'
        AND pap.PAYROLL_NAME = 'TELKOMSEL'
        AND (pec.legislation_code IS NULL OR pec.legislation_code = 'ID')
      
        -- AND ROWNUM <= 1
        GROUP BY pap.PAYROLL_NAME, ppa.effective_date, pet.element_name
        ORDER BY pap.PAYROLL_NAME, ppa.effective_date ASC
        ")->queryAll();


        var_dump($query);
    }

}
