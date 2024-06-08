<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Database;
use App\UserApi;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/ipack');
$database = new Database();
$userApi = new UserApi($database);



$app->post('/api/user/login', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $user = isset($data['user'])? $data['user'] :'';
    $pswd = isset($data['pswd'])? $data['pswd'] :'';
    $result = $userApi->UserLogin($user, $pswd);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/validate', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $user = isset($data['user'])? $data['user'] :'';
    $result = $userApi->user_valid($user);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/token_update', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $ptokenno = isset($data['ptokenno'])? $data['ptokenno'] :'';
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pmodule = isset($data['pmodule'])? $data['pmodule'] :'';
    $palertcode = isset($data['palertcode'])? $data['palertcode'] :'';
    $puser = isset($data['puser'])? $data['puser'] :'';
    $result = $userApi->hide_alert($ptokenno, $pcomp_code, $pdiv_code, $pmodule, $palertcode, $puser);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->post('/api/user/get_doc_level', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $pdoccode = isset($data['pdoccode'])? $data['pdoccode'] :'';
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pmodule = isset($data['pmodule'])? $data['pmodule'] :'';
    $pdocno = isset($data['pdocno'])? $data['pdocno'] :'';
    $pintdocno = isset($data['pintdocno'])? $data['pintdocno'] :'';
    $puser = isset($data['puser'])? $data['puser'] :'';
    $result = $userApi->get_doc_level($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $puser);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/Doc_Authorise', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $ptokenno = isset($data['ptokenno'])? $data['ptokenno'] :'';
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pmodule = isset($data['pmodule'])? $data['pmodule'] :'';
    $pdoccode = isset($data['pdoccode'])? $data['pdoccode'] :'';
    $pdocno = isset($data['pdocno'])? $data['pdocno'] :'';
    $pintdocno = isset($data['pintdocno'])? $data['pintdocno'] :'';
    $plvl = isset($data['plvl'])? $data['plvl'] :'';
    $puser = isset($data['puser'])? $data['puser'] :'';
    $result = $userApi->Doc_Authorise($ptokenno, $pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $plvl, $puser);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->post('/api/user/Modification_Request_Authorise', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $ptokenno = isset($data['ptokenno'])? $data['ptokenno'] :'';
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pmodule = isset($data['pmodule'])? $data['pmodule'] :'';
    $pdoccode = isset($data['pdoccode'])? $data['pdoccode'] :'';
    $pdocno = isset($data['pdocno'])? $data['pdocno'] :'';
    $pintdocno = isset($data['pintdocno'])? $data['pintdocno'] :'';
    $palertcode = isset($data['palertcode'])? $data['palertcode'] :'';
    $puser = isset($data['puser'])? $data['puser'] :'';
    $ptrguser = isset($data['ptrguser'])? $data['ptrguser'] :'';
    $presponse = isset($data['presponse'])? $data['presponse'] :'';
    $pskey = isset($data['pskey'])? $data['pskey'] :'';
    $result = $userApi->Modification_Request_Authorise($ptokenno, $pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $puser, $palertcode, $ptrguser, $presponse, $pskey);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/Request_Reject', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pmodule = isset($data['pmodule'])? $data['pmodule'] :'';
    $pdoccode = isset($data['pdoccode'])? $data['pdoccode'] :'';
    $pdocno = isset($data['pdocno'])? $data['pdocno'] :'';
    $pintdocno = isset($data['pintdocno'])? $data['pintdocno'] :'';
    $palertcode = isset($data['palertcode'])? $data['palertcode'] :'';
    $plvl = isset($data['plvl'])? $data['plvl'] :'';
    $ptrguser = isset($data['ptrguser'])? $data['ptrguser'] :'';
    $ptokenno = isset($data['ptokenno'])? $data['ptokenno'] :'';
    $result = $userApi->Request_Reject($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $pdocno, $pintdocno, $palertcode, $ptrguser, $plvl, $ptokenno);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/salesman_valid', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $psaleman = isset($data['psaleman'])? $data['psaleman'] :'';
    $pusercode = isset($data['pusercode'])? $data['pusercode'] :'';
    $result = $userApi->salesman_valid($pcomp_code, $pdiv_code, $psaleman, $pusercode);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/user/get_alert_type', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $result = $userApi->get_alert_type();
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->post('/api/user/lvr_autherise', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pmodule = isset($data['pmodule'])? $data['pmodule'] :'';
    $pdoccode = isset($data['pdoccode'])? $data['pdoccode'] :'';
    $puser = isset($data['puser'])? $data['puser'] :'';
    $pdocdate = isset($data['pdocdate'])? $data['pdocdate'] :'';
    $pempcode = isset($data['pempcode'])? $data['pempcode'] :'';
    $pstartdate = isset($data['pstartdate'])? $data['pstartdate'] :'';
    $penddate = isset($data['penddate'])? $data['penddate'] :'';
    $ppurpose = isset($data['ppurpose'])? $data['ppurpose'] :'';
    $result = $userApi->Lvr_Autherise($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $puser, $pdocdate, $pempcode, $pstartdate, $penddate, $ppurpose);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/sales_visit_save', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pmodule = isset($data['pmodule'])? $data['pmodule'] :'';
    $pdoccode = isset($data['pdoccode'])? $data['pdoccode'] :'';
    $puser = isset($data['puser'])? $data['puser'] :'';
    $psaleman = isset($data['psaleman'])? $data['psaleman'] :'';
    $pdocdate = isset($data['pdocdate'])? $data['pdocdate'] :'';
    $pleadname = isset($data['pleadname'])? $data['pleadname'] :'';
    $pleadpic = isset($data['pleadpic'])? $data['pleadpic'] :'';
    $paddress = isset($data['paddress'])? $data['paddress'] :'';
    $pphone = isset($data['pphone'])? $data['pphone'] :'';
    $pemail = isset($data['pemail'])? $data['pemail'] :'';
    $premarks = isset($data['premarks'])? $data['premarks'] :'';
    $ptype = isset($data['ptype'])? $data['ptype'] :'';
    $pshipmenttype = isset($data['pshipmenttype'])? $data['pshipmenttype'] :'';
    $platitude = isset($data['platitude'])? $data['platitude'] :'';
    $pladdress = isset($data['pladdress'])? $data['pladdress'] :'';

    $result = $userApi->Sales_Visit_Save($pcomp_code, $pdiv_code, $pmodule, $pdoccode, $puser, $psaleman, $pdocdate, $pleadname, $pleadpic, $paddress, 
    $pphone, $pemail, $premarks, $ptype, $pshipmenttype, $platitude, $pladdress);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/getnotifications', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $pdocno = isset($data['pdocno'])? $data['pdocno'] :'';
    $puser = isset($data['puser'])? $data['puser'] :'';
    $palert = isset($data['palert'])? $data['palert'] :'';
    $presponse = isset($data['presponse'])? $data['presponse'] :'';
    $pdate = isset($data['pdate'])? $data['pdate'] :'';
    $palertrm = isset($data['palertrm'])? $data['palertrm'] :'';
    $result = $userApi->getnotificationdet($pcomp_code, $pdiv_code, $pdocno, $puser, $palert, $presponse, $pdate, $palertrm);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/user/notify_doc_details', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $pcomp_code = isset($data['pcomp_code'])? $data['pcomp_code'] :'';
    $pdiv_code = isset($data['pdiv_code'])? $data['pdiv_code'] :'';
    $ptokenno = isset($data['ptokenno'])? $data['ptokenno'] :'';
    $pusercode = isset($data['pusercode'])? $data['pusercode'] :'';
    $presponse = isset($data['presponse'])? $data['presponse'] :'';
    $result = $userApi->Notify_Doc_details($pcomp_code, $pdiv_code, $ptokenno, $pusercode, $presponse);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->post('/api/user/getusers', function (Request $request, Response $response) use ($userApi) {
    $data = $request->getParsedBody();
    $user = isset($data['user'])? $data['user'] :'';
    $com_code = isset($data['com_code'])? $data['com_code'] :'';
    $result = $userApi->get_user_det($user, $com_code);
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/user/validate/{user}', function (Request $request, Response $response, $args) use ($userApi) {
    $response->getBody()->write("Test route works!".$args['user']);
    return $response;
});

$app->get('/test', function (Request $request, Response $response, $args) use ($userApi) {
    $response->getBody()->write("Test route works!");
    return $response;
});

$app->get('/test/userresult', function (Request $request, Response $response, $args) use ($userApi) {
    $result = $userApi->userResult();
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
