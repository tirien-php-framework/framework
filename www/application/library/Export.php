<?php
Class Export
{
    public static function toXLS( $data, $columns, $file_name = false )
    {

        $out = '<table border="1">';


        // header
        $out .= '<tr bgcolor="#666666">';
        foreach ($columns as $value) {
            $out.= '<th><font color="#FFFFFF">'.$value.'</font></th>';
        }
        $out .=  '</tr>';


        // rows
        foreach ($data as $row) {
            $out.="<tr>";           
            foreach ($row as $value) {
                $out.="<td>".$value."</td>";
            }
            $out.="</tr>";
        }


        $out.="</table>";

        $out = '<html><head><meta http-equiv="Content-Type" content="text/html;" charset="utf-8"></head><body>'.$out.'</body></html>';
 

        // export
        if ($file_name === false)
        {
            $now = date("m_d_Y");
            $file_name = "export_{$now}.xls";    
        }

        header("Content-type: application/vnd-ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename='".$file_name."'");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $out;
    }    
}