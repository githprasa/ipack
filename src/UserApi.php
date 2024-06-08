<?php
namespace App;

class UserApi {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function userResult() {
        $response=[];
        $response['status']=false;
        $response['message']='';
        try {
            $query = "select * from gusercomp";  //guser
            $conn = $this->db->connect();
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $row_result='';
            $row_result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $row_result;
        } catch (\Exception $e) {
            $response['message']='Error : ' . $e->getMessage();
            $response['file']= $e->getFile();
            $response['line number']=$e->getLine();
            $response['logResult']=-1;
            return $response;
        } finally {
            $this->db->close();
        }
    }

    public function UserLogin($user, $pswd) {
        $response=[];
        $response['status']=false;
        $response['message']='';
        try {
            $query = "select pwd from guser where active = 'Y' and upper(usercode) = '" . strtoupper($user) . "'";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $row_result='';
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $row_result=$row;
            }
            $upwd = "#";
            if($row_result) {
                $upwd = isset($row_result['PWD'])? $row_result['PWD']:'';
            } else {
                $response['message']='Invalid User';
                $response['logResult']=1;
                return $response;
            }
            $epwd=0;
            $call = "BEGIN :result := application.pwd_encrypt(:pswd); END;";
            $cstmt = $conn->prepare($call);
            $cstmt->bindParam(':result', $epwd, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 4000);
            $cstmt->bindParam(':pswd', $pswd, \PDO::PARAM_STR);
            $cstmt->execute();
            if ($epwd == $upwd) {
                $response['status']=true;
                $response['message']='User Logged in';
                $response['logResult']=0;
                return $response;
            }else {
                $response['message']='Invalid Password';
                $response['logResult']=0;
                return $response;
            }
            $this->db->close();
        } catch (\Exception $e) {
            $response['message']='Error : ' . $e->getMessage();
            $response['file']= $e->getFile();
            $response['line number']=$e->getLine();
            $response['logResult']=-1;
            return $response;
        } finally {
            $this->db->close();
        }
    }

    public function user_valid($puser) {
        try {
            $response=[];
            $response['status']=false;
            $user_valid=0;
            $conn = $this->db->connect();
            $call = "BEGIN :result := iPack_App_status.user_valid(:puser); END;";
            $cstmt = $conn->prepare($call);
            $cstmt->bindParam(':result', $user_valid, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 4000);
            $cstmt->bindParam(':puser', $puser, \PDO::PARAM_STR);
            $cstmt->execute();
            $response['status']=true;
            $response['user_valid']=$user_valid;
            return $response;
        } catch (\Exception $e) {
            $response['message']='Error : ' . $e->getMessage();
            $response['file']= $e->getFile();
            $response['line number']=$e->getLine();
            $response['logResult']=-1;
            return $response;
        } finally {
            $this->db->close();
        }
    }

    public function hide_alert($ptokenno, $pcomp_code, $pdiv_code, $pmodule, $palertcode, $puser) {
        $response=[];
        $response['token_update'] = 0;
        try {
            $conn = $this->db->connect(); // Assuming $this->db->connect() establishes a database connection
            $call = "BEGIN IPACK_ALERT.hide_alert(:ptokenno, :pcomp_code, :pdiv_code, :pmodule, :palertcode, :puser); END;";
            $cstmt = $conn->prepare($call);
            $cstmt->bindParam(':ptokenno', $ptokenno, \PDO::PARAM_INT);
            $cstmt->bindParam(':pcomp_code', $pcomp_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdiv_code', $pdiv_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pmodule', $pmodule, \PDO::PARAM_STR);
            $cstmt->bindParam(':palertcode', $palertcode, \PDO::PARAM_STR);
            $cstmt->bindParam(':puser', $puser, \PDO::PARAM_STR);
            $cstmt->execute();
        } catch (\Exception $e) {
            $token_update = 1; 
            $response['message'] = "Exception Database Connection Failed: " . $e->getMessage();
            $response['file'] = "Error File: " . $e->getFile() . " Line: " . $e->getLine();
            return $response;
        } finally {
            $this->db->close();
            $conn = $cstmt= null; // Close the connection by destroying the object
        }
        return $response;
    }


    public function get_doc_levels($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $puser) {
        $response = [];
        try {
            $response['status'] = false;
            $response['get_doc_levels'] = 0;
            $conn = $this->db->connect();
            $call = "BEGIN :result := ipack_alert.get_doc_level(:pcomp_code, :pdiv_code, :pmodule, :pdoccode, :pdocno, :pintdocno, :puser); END;";
            $cstmt = $conn->prepare($call);
            $get_doc_levels = null;
            $cstmt->bindParam(':result', $get_doc_levels, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 4000);
            $cstmt->bindParam(':pcomp_code', $pcomp_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdiv_code', $pdiv_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pmodule', $pmodule, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdoccode', $pdoccode, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdocno', $pdocno, \PDO::PARAM_STR);
            $cstmt->bindParam(':pintdocno', $pintdocno, \PDO::PARAM_INT);
            $cstmt->bindParam(':puser', $puser, \PDO::PARAM_STR);
            $cstmt->execute();
            $response['status'] = true;
            $response['get_doc_levels'] = $get_doc_levels;
            return $response;
        } catch (\Exception $e) {
            $response['message'] = 'Error : ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line number'] = $e->getLine();
            $response['logResult'] = -1;
        } finally {
            // Ensure the connection is closed
            $this->db->close();
            $conn = $cstmt= null;
        }
        return $response;
    }

    public function get_doc_level($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $puser) {
        $response = [];
        try {
            $conn = $this->db->Oci_connection();
            $stmt = oci_parse($conn, "BEGIN :result := ipack_alert.get_doc_level(:pcomp_code, :pdiv_code, :pmodule, :pdoccode, :pdocno, :pintdocno, :puser); END;");
            $result = null;
            oci_bind_by_name($stmt, ":result", $result, -1, SQLT_INT);
            oci_bind_by_name($stmt, ":pcomp_code", $pcomp_code);
            oci_bind_by_name($stmt, ":pdiv_code", $pdiv_code);
            oci_bind_by_name($stmt, ":pmodule", $pmodule);
            oci_bind_by_name($stmt, ":pdoccode", $pdoccode);
            oci_bind_by_name($stmt, ":pdocno", $pdocno);
            oci_bind_by_name($stmt, ":pintdocno", $pintdocno);
            oci_bind_by_name($stmt, ":puser", $puser);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                $response['message'] = 'Failed to execute stored procedure: ' . $e['message'];
                $response['status'] = false;
                return $response;
            }

            $response['status'] = true;
            $response['get_doc_levels'] = $result;
        } catch (\Exception $e) {
            $response['message'] = 'Error : ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            // Free the statement and close the connection
            oci_free_statement($stmt);
            oci_close($conn);
        }
        return $response;
    }


    public function Doc_Authorise($ptokenno, $pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $plvl, $puser) {
        $response = [];
        try {
            // Establish database connection
            $conn = $this->db->connect();
            
            // Prepare the stored procedure call
            $call = "BEGIN ipack_alert.doc_status_updates(:ptokenno, :pcomp_code, :pdiv_code, :pmodule, :pdoccode, :pdocno, :pintdocno, :plvl, :puser, :perror); END;";
            $cstmt = $conn->prepare($call);
            
            // Bind the parameters to the statement
            // $cstmt->bindParam(':result', $perror, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 4000);
            $cstmt->bindParam(':ptokenno', $ptokenno, \PDO::PARAM_INT);
            $cstmt->bindParam(':pcomp_code', $pcomp_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdiv_code', $pdiv_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pmodule', $pmodule, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdoccode', $pdoccode, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdocno', $pdocno, \PDO::PARAM_STR);
            $cstmt->bindParam(':pintdocno', $pintdocno, \PDO::PARAM_INT);
            $cstmt->bindParam(':plvl', $plvl, \PDO::PARAM_INT);
            $cstmt->bindParam(':puser', $puser, \PDO::PARAM_STR);
            $perror = '';
            $cstmt->bindParam(':perror', $perror, \PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 4000);
            $cstmt->execute();
            $response['status'] = true;
            $response['error'] = $perror;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        }  finally {
            // Ensure the connection is closed
            $this->db->close();
            $conn = $cstmt= null;
        }
    
        return $response;
    }

    
    public function Modification_Request_Authorise($ptokenno, $pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $puser, $palertcode, $ptrguser, $presponse, $pskey) {
        $response = [];
        try {
            // Establish database connection
            $conn = $this->db->connect();
    
            // Prepare the stored procedure call
            $call = "BEGIN ipack_alert.requests_response_alert(:ptokenno, :pcomp_code, :pdiv_code, :pmodule, :pdoccode, :pdocno, :pintdocno, :puser, :palertcode, :ptrguser, :presponse, :pskey); END;";
            $cstmt = $conn->prepare($call);
    
            // Bind the parameters to the statement
            $cstmt->bindParam(':ptokenno', $ptokenno, \PDO::PARAM_INT);
            $cstmt->bindParam(':pcomp_code', $pcomp_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdiv_code', $pdiv_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pmodule', $pmodule, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdoccode', $pdoccode, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdocno', $pdocno, \PDO::PARAM_STR);
            $cstmt->bindParam(':pintdocno', $pintdocno, \PDO::PARAM_INT);
            $cstmt->bindParam(':puser', $puser, \PDO::PARAM_STR);
            $cstmt->bindParam(':palertcode', $palertcode, \PDO::PARAM_STR);
            $cstmt->bindParam(':ptrguser', $ptrguser, \PDO::PARAM_STR);
            $cstmt->bindParam(':presponse', $presponse, \PDO::PARAM_STR);
            $cstmt->bindParam(':pskey', $pskey, \PDO::PARAM_STR);
            // Execute the stored procedure
            $cstmt->execute();
    
            // Set response status to success
            $response['status'] = true;
        } catch (\Exception $e) {
            // Handle general Exception
            $response['status'] = false;
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
        } finally {
            // Ensure the connection is closed
            $this->db->close();
            $conn = $cstmt= null;
        }
    
        return $response;
    }

    public function Request_Reject($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $palertcode, $ptrguser, $plvl, $ptokenno) {
        $response = [];
        try {
            // Establish database connection
            $conn = $this->db->connect();
    
            // Prepare the stored procedure call with an output parameter
            $call = "BEGIN :reject_request := ipack_alert.create_alert(:pcomp_code, :pdiv_code, :pmodule, :pdoccode, :pdocno, :pintdocno, :palertcode, :ptrguser, :plvl, :ptokenno); END;";
            $cstmt = $conn->prepare($call);
    
            // Initialize and bind output parameter
            $reject_request = 0;
            $cstmt->bindParam(':reject_request', $reject_request, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 4000);
    
            // Bind input parameters
            $cstmt->bindParam(':pcomp_code', $pcomp_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdiv_code', $pdiv_code, \PDO::PARAM_STR);
            $cstmt->bindParam(':pmodule', $pmodule, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdoccode', $pdoccode, \PDO::PARAM_STR);
            $cstmt->bindParam(':pdocno', $pdocno, \PDO::PARAM_STR);
            $cstmt->bindParam(':pintdocno', $pintdocno, \PDO::PARAM_INT);
            $cstmt->bindParam(':palertcode', $palertcode, \PDO::PARAM_STR);
            $cstmt->bindParam(':ptrguser', $ptrguser, \PDO::PARAM_STR);
            $cstmt->bindParam(':plvl', $plvl, \PDO::PARAM_INT);
            $cstmt->bindParam(':ptokenno', $ptokenno, \PDO::PARAM_INT);
            // Execute the stored procedure
            $cstmt->execute();
            // Check result from the output parameter
            $response['reject_request'] = $reject_request;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['reject_request']=0;
            $response['message'] = 'Error : ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['logResult'] = -1;
            $response['status'] = false;
        } finally {
            // Ensure the connection is closed
            $this->db->close();
            $conn = $cstmt= null;
        }
    
        return $response;
    }

    public function salesman_valid($pcomp_code, $pdiv_code, $psaleman, $pusercode) {
        $response = [];
        try {
            $conn = $this->db->Oci_connection();
            $stmt = oci_parse($conn, "BEGIN :salesman_valid := iPack_App_status.Salesman_valid(:pcomp_code, :pdiv_code, :pusercode, :psaleman); END;");
            
            oci_bind_by_name($stmt, ":salesman_valid", $salesman_valid, 4000);
            oci_bind_by_name($stmt, ":pcomp_code", $pcomp_code);
            oci_bind_by_name($stmt, ":pdiv_code", $pdiv_code);
            oci_bind_by_name($stmt, ":pusercode", $pusercode);
            oci_bind_by_name($stmt, ":psaleman", $psaleman);
            
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                $response['message'] = 'Failed to execute stored procedure: ' . $e['message'];
                $response['status'] = false;
                return $response;
            }
            
            $response['salesman_valid'] = $salesman_valid;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            oci_free_statement($stmt);
            oci_close($conn);
            $this->db->close();
            $conn = $stmt = null;
        }
        return $response;
    }

    public function get_alert_type() {
        $response = [];
        try {
            $conn = $this->db->Oci_connection();
            $stmt = oci_parse($conn, "BEGIN DEMO.IPACK_APP_STATUS.GET_ALERTTYPE(:cursor); END;");
            $cursor = oci_new_cursor($conn);
            oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
            oci_execute($stmt);
            oci_execute($cursor);
            $result_data = []; $i=0;
            while ($row = oci_fetch_array($cursor, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $ALERTCODE = $row['ALERTCODE'] ?? '';
                $ALERTNAME = $row['ALERTNAME'] ?? '';
                $result_data[$i]['ALERTCODE'] = $ALERTCODE;
                $result_data[$i]['ALERTNAME'] = $ALERTNAME;
                $i++;
            }
            $response['data'] = $result_data;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: Database Query Execution Failed! ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            oci_free_statement($cursor);
            oci_free_statement($stmt);
            oci_close($conn);
            $this->db->close();
            $conn = $stmt= null;
        }
        return $response;
    }

    public function Lvr_Autherise($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $puser, $pdocdate, $pempcode, $pstartdate, $penddate, $ppurpose) {
        $response = [];
        try {
            $conn = $this->db->Oci_connection();
            $call = "BEGIN iPack_App_status.Lvr_Autherise(:pcomp_code, :pdiv_code, :pmodule, :pdoccode, :puser, :pdocdate, :pempcode, :pstartdate, :penddate, :ppurpose, :out_param1, :out_param2); END;";
            $stmt = oci_parse($conn, $call);
            oci_bind_by_name($stmt, ":pcomp_code", $pcomp_code);
            oci_bind_by_name($stmt, ":pdiv_code", $pdiv_code);
            oci_bind_by_name($stmt, ":pmodule", $pmodule);
            oci_bind_by_name($stmt, ":pdoccode", $pdoccode);
            oci_bind_by_name($stmt, ":puser", $puser);
            oci_bind_by_name($stmt, ":pdocdate", $pdocdate);
            oci_bind_by_name($stmt, ":pempcode", $pempcode);
            oci_bind_by_name($stmt, ":pstartdate", $pstartdate);
            oci_bind_by_name($stmt, ":penddate", $penddate);
            oci_bind_by_name($stmt, ":ppurpose", $ppurpose);
            $out_param1 = '';
            $out_param2 = '';
            oci_bind_by_name($stmt, ":out_param1", $out_param1, 4000);
            oci_bind_by_name($stmt, ":out_param2", $out_param2, 4000);
            oci_execute($stmt);
            $response['autherise'] = [$out_param1, $out_param2];
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            oci_free_statement($stmt);
            oci_close($conn);
            $this->db->close();
            $conn = $stmt= null;
        }
        return $response;
    }

    public function Sales_Visit_Save($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $puser, $psaleman, $pdocdate, $pleadname, $pleadpic, $paddress, $pphone, $pemail, $premarks, $ptype, $pshipmenttype, $platitude, $pladdress) {
        $response = [];
        try {
            $conn = $this->db->Oci_connection();
            $stmt = oci_parse($conn, "BEGIN :result := iPack_App_status.sales_visit(:pcomp_code, :pdiv_code, :pmodule, :pdoccode, :puser, :psaleman, :pdocdate, :pleadname, :pleadpic, :paddress, :pphone, :pemail, :premarks, :ptype, :pshipmenttype, :platitude, :pladdress); END;");
            oci_bind_by_name($stmt, ":result", $result, 32, SQLT_INT);
            oci_bind_by_name($stmt, ":pcomp_code", $pcomp_code);
            oci_bind_by_name($stmt, ":pdiv_code", $pdiv_code);
            oci_bind_by_name($stmt, ":pmodule", $pmodule);
            oci_bind_by_name($stmt, ":pdoccode", $pdoccode);
            oci_bind_by_name($stmt, ":puser", $puser);
            oci_bind_by_name($stmt, ":psaleman", $psaleman);
            oci_bind_by_name($stmt, ":pdocdate", $pdocdate);
            oci_bind_by_name($stmt, ":pleadname", $pleadname);
            oci_bind_by_name($stmt, ":pleadpic", $pleadpic);
            oci_bind_by_name($stmt, ":paddress", $paddress);
            oci_bind_by_name($stmt, ":pphone", $pphone);
            oci_bind_by_name($stmt, ":pemail", $pemail);
            oci_bind_by_name($stmt, ":premarks", $premarks);
            oci_bind_by_name($stmt, ":ptype", $ptype);
            oci_bind_by_name($stmt, ":pshipmenttype", $pshipmenttype);
            oci_bind_by_name($stmt, ":platitude", $platitude);
            oci_bind_by_name($stmt, ":pladdress", $pladdress);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                $response['message'] = 'Failed to execute stored procedure: ' . $e['message'];
                $response['status'] = false;
                return $response;
            }
            $response['message'] = $result;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            oci_free_statement($stmt);
            oci_close($conn);
            $this->db->close();
            $conn = $stmt= null;
        }
        return $response;
    }
    

    public function getnotificationdet($pcomp_code, $pdiv_code, $pdocno, $puser, $palert, $presponse, $pdate, $palertrm) {
        $response = [];
        try {
            // if(empty($pdocno)) $pdocno=null;
            // if(empty($pdate)) $pdate=null;
            // $response['passingdata'] = [$pcomp_code, $pdiv_code, $pdocno, $puser, $palert, $presponse, $pdate, $palertrm];
            $conn = $this->db->Oci_connection();
            $call = "BEGIN iPack_App_status.getpndnfc(:pcomp_code, :pdiv_code, :pdocno, :puser, :palert, :presponse, :pdate, :palertrm, :out_cursor); END;";
            $stmt = oci_parse($conn, $call);
            oci_bind_by_name($stmt, ":pcomp_code", $pcomp_code);
            oci_bind_by_name($stmt, ":pdiv_code", $pdiv_code);
            oci_bind_by_name($stmt, ":pdocno", $pdocno);
            oci_bind_by_name($stmt, ":puser", $puser);
            oci_bind_by_name($stmt, ":palert", $palert);
            oci_bind_by_name($stmt, ":presponse", $presponse);
            oci_bind_by_name($stmt, ":pdate", $pdate);
            oci_bind_by_name($stmt, ":palertrm", $palertrm);

            // For the cursor output
            $cursor = oci_new_cursor($conn);
            oci_bind_by_name($stmt, ":out_cursor", $cursor, -1, OCI_B_CURSOR);

            oci_execute($stmt);
            oci_execute($cursor, OCI_DEFAULT);

            $results = [];
            while (($row = oci_fetch_array($cursor, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                $results[] = $row;
            }

            $response['results'] = $results;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            oci_free_statement($stmt);
            oci_close($conn);
            $this->db->close();
            $conn = $stmt= null;
        }
        return $response;
    }


    public function get_user_det($user, $com_code) {
        $response = [];
        try {
            // $response['passingdata'] =[$user, $com_code];
            $sql = "SELECT g.comp_code, g.div_code, u.wscode, u.empcode, u.smancode
            FROM guser u, gusercomp g
            WHERE u.usercode = :usercode
            AND u.usercode = g.usercode
            AND g.defacomp = :com_code";

            $conn = $this->db->Oci_connection();
            
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':usercode', $user);
            oci_bind_by_name($stmt, ':com_code', $com_code);
            oci_execute($stmt);
            $ins_comp=[];
            if ($row = oci_fetch_assoc($stmt)) {                
                $ins_comp[]=$row;
            }
            $response['results'] = $ins_comp;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            oci_free_statement($stmt);
            oci_close($conn);
            $this->db->close();
            $conn = $stmt= null;
        }
        return $response;
    }


    public function Notify_Doc_details($pcomp_code, $pdiv_code,$ptokenno, $pusercode,$presponse) {
        $response = [];
        try {
            //print_r([$pcomp_code, $pdiv_code,$ptokenno, $pusercode,$presponse]);exit;
            $conn = $this->db->Oci_connection();
            $call = "BEGIN iPack_App_status.Notify_Doc_details(:pcomp_code, :pdiv_code, :ptokenno, :pusercode, :presponse, :out1, :out2, :out3, :out4, :out5, :out6, :out7, :out8, :out9, :out10, :out11); END;";
            $stmt = oci_parse($conn, $call);
            oci_bind_by_name($stmt, ":pcomp_code", $pcomp_code);
            oci_bind_by_name($stmt, ":pdiv_code", $pdiv_code);
            oci_bind_by_name($stmt, ":ptokenno", $ptokenno);
            oci_bind_by_name($stmt, ":pusercode", $pusercode);
            oci_bind_by_name($stmt, ":presponse", $presponse);
            
            $out = [];
            for ($i = 1; $i <= 11; $i++) {
                $out[$i] = '';
                oci_bind_by_name($stmt, ":out$i", $out[$i], 4000);
            }
            
            // Execute the statement
            oci_execute($stmt);
            
            // Gather results from the output parameters
            $customer = [];
            for ($i = 1; $i <= 11; $i++) {
                $customer[] = $out[$i];
            }
            $response['results'] = $customer;
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['error'] = 'Exception: ' . $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line_number'] = $e->getLine();
            $response['status'] = false;
        } finally {
            oci_free_statement($stmt);
            oci_close($conn);
            $this->db->close();
            $conn = $stmt= null;
        }
        return $response;
    }

}
