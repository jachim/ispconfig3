<?php

/*
Copyright (c) 2007, Till Brehm, projektfarm Gmbh
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice,
      this list of conditions and the following disclaimer in the documentation
      and/or other materials provided with the distribution.
    * Neither the name of ISPConfig nor the names of its contributors
      may be used to endorse or promote products derived from this software without
      specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class plugin_dbhistory extends plugin_base {

        var $module;
        var $form;
        var $tab;
        var $record_id;
        var $formdef;
        var $options;

        function onShow() {
		
			global $app, $conf;
			
			$content = '';
			
			$db_table = $app->tform->formDef["db_table"];
			$db_table_idx = $app->tform->formDef["db_table_idx"];
			$primary_id = $this->form->id;
			if($_SESSION["s"]["user"]["typ"] == 'admin') {
				$sql = "SELECT action, tstamp, user, data FROM sys_datalog WHERE dbtable = '".$db_table."' AND dbidx = '".$db_table_idx.":".$primary_id."'";
			} else {
				$sql = "SELECT action, tstamp, user, data FROM sys_datalog WHERE user = '".$_SESSION["s"]["user"]["username"]."' dbtable = '".$db_table."' AND dbidx = '".$db_table_idx.":".$primary_id."'";
			}
			
			$records = $app->db->queryAllRecords($sql);
			if(is_array($records)) {
				$content .= '<table>';
				foreach($records as $rec) {
					$content .= "<tr><td>".date("d.m.Y",$rec["tstamp"])."</td><td>".$rec["user"]."</td></tr>";
				}
				$content .= '</table>';
			}
			
			return $content;

        }
}

?>