<?php
	if (isset($_SERVER['HTTP_ORIGIN'])) {
	    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	    header('Access-Control-Allow-Credentials: true');
	    header('Access-Control-Max-Age: 86400');
	}
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	}

	session_start();
	include("Functions.php");
	$Functions = new DatabaseClasses;

	if (isset($_GET['auth'])){ /**/
		print_r('162165146157156147141142162151154154157152162');
	}

	if (isset($_GET['kill-session'])){ /**/
		if(isset($_POST["data"])){
			print_r(session_destroy());
		}
		else{
			echo "Hacker";
		}
	}

	if(isset($_GET['check-login'])){ /**/
		print_r(json_encode($_SESSION['kareer7836']));
	}

	if(isset($_GET['validateEmail'])){/**/
		$data = $_POST['data'];
        $access = $Functions->escape($data);
		$query = $Functions->PDO("SELECT count(*) FROM tbl_businessManagers INNER JOIN tbl_applicant ON tbl_applicant.email = tbl_businessManagers.email WHERE tbl_businessManagers.email = {$access}");
		print_r($query[0][0]);
	}

	if(isset($_GET['validateEmployer'])){/**/
		$data = $_POST['data'];
		$count = 0;
		$query = $Functions->PDO("SELECT count(*) FROM tbl_employer WHERE email = '{$data}'");
		$count = $count + $query[0][0];
		print_r($count);
	}

	if(isset($_GET['send-mail'])){/**/
		$data = $_POST['data'];
		$message = "<div style='text-align: center;width: 500px;position: relative;margin: 0 auto;border-radius: 3px;background: #4485F4;color: #fff;padding: 30px;border-top: yellow solid 10px;top: 50px;box-shadow: 0px 0px 50px #ccc;margin-top: 50px;margin-bottom: 50px;'><b><font size='6'>Welcome to Kareer</font></b><br/><br/><br/>Thank you for registering to Kareer. Here is your&nbsp;system generated password: {$data[1]}&nbsp;<br/><br/><br/>Please change your password as soon as you get in to your account. <br/><br/><br/><br/>Thanks and God bless.</div> ";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Kareer' . "\r\n";
		$subject = 'Kareer - Applicant Account Registration';
		$result = mail($data[0],$subject,$message,$headers);
	}

	if (isset($_GET['login'])){/**/
		$data = $_POST['data']; $flag = 0;
        $access = $Functions->escape($data[0]);
        $password = $data[1];
		$query = $Functions->PDO("SELECT * FROM tbl_businessManagers WHERE email = {$access}");
		if(count($query)==1){
            if($Functions->testPassword($password,$query[0][4]) && ($query[0][8] == 1)){
				$_SESSION["kareer7836"] = [$query[0][0],$access,'employer'];
                print_r(json_encode($_SESSION["kareer7836"]));
            }
		}
		else{
			$query = $Functions->PDO("SELECT * FROM tbl_admin WHERE username = {$access}");
			if(count($query)==1){
	            if($Functions->testPassword($password,$query[0][6]) && ($query[0][7] == 1)){
	                $_SESSION["kareer7836"] = [$query[0][0],$query[0][4],'admin'];
	                print_r(json_encode($_SESSION["kareer7836"]));
	            }
			}
		}
	}

	if(isset($_GET['get-session'])){/**/
		if(isset($_SESSION['kareer7836']))
			print_r(json_encode($_SESSION['kareer7836']));
		else
			print_r(0);
	}

	if(isset($_GET['get-jobPost'])){ /**/
		$data = $_POST['data'];
		$query = $Functions->PDO("SELECT * FROM tbl_vacancies WHERE id = '{$data}'");
		print_r(json_encode($query));
	}

	if(isset($_GET['get-employerJobsPosts'])){ /**/
		$data = $_POST['data'];
		$query = $Functions->PDO("SELECT a.id, a.status, a.job_title, c.name, a.skills, a.salary_min, a.salary_max FROM tbl_vacancies a LEFT JOIN tbl_business b ON a.business_id = b.id LEFT JOIN tbl_businessmanagers c ON c.id = a.employer_id");
		print_r(json_encode($query));
	}

	if (isset($_GET['get-account'])){/**/
		$session = $_SESSION['kareer7836'];
		$query = $Functions->PDO("SELECT * FROM tbl_businessmanagers  WHERE id = '{$session[0]}'");
		if(count($query)==0){
			$query = $Functions->PDO("SELECT * FROM tbl_admin  WHERE id = '{$session[0]}'");
			if(count($query)==0){
				echo 0;
			}
			else if(count($query)==1){
				print_r(json_encode($query));
			}
		}
		else if(count($query)==1){
			print_r(json_encode($query));
		}
	}

	if (isset($_GET['get-accountBusinessManager'])){/**/
		$session = $_SESSION['kareer7836'];
		$query = $Functions->PDO("SELECT * FROM tbl_businessmanagers  WHERE id = '{$session[0]}'");
		if(count($query)==0){
			echo 0;
		}
		else if(count($query)==1){
			print_r(json_encode($query));
		}
	}

	if (isset($_GET['get-businessList'])){/**/
		$q = $Functions->PDO("SELECT * FROM tbl_business");
		print_r(json_encode($q));
	}

	if (isset($_GET['get-businessInfo'])){/**/
		$data = $_POST['data'];
		$q = $Functions->PDO("SELECT * FROM tbl_business WHERE id = '{$data}'");
		print_r(json_encode($q));
	}

	if (isset($_GET['get-applicantInfo'])){/**/
		$data = $_POST['data'];
		$q = $Functions->PDO("SELECT * FROM tbl_applicant LEFT JOIN tbl_personalinfo ON tbl_applicant.id = tbl_personalinfo.id WHERE tbl_applicant.id = '{$data}'");
		print_r(json_encode($q));
	}

	if (isset($_GET['get-applicantAcad'])){/**/
		$data = $_POST['data'];
		$q = $Functions->PDO("SELECT tbl_acadinfo.date,tbl_acadinfo.level,tbl_acadinfo.schoolattended,tbl_acadinfo.degree,tbl_acadinfo.periodofattendance,tbl_acadinfo.highestlevel,tbl_acadinfo.yeargraduated  FROM tbl_applicant LEFT JOIN tbl_acadinfo ON tbl_applicant.id = tbl_acadinfo.applicant_id WHERE tbl_applicant.id = '{$data}' ORDER BY tbl_acadinfo.yeargraduated ASC");
		print_r(json_encode($q));
	}

	if (isset($_GET['get-applicantCareer'])){/**/
		$data = $_POST['data'];
		$q = $Functions->PDO("SELECT tbl_career.date,tbl_career.inclusive_fromdate,tbl_career.inclusive_todate,tbl_career.position_title,tbl_career.agency,tbl_career.monthly_salary,tbl_career.appointment_status,tbl_career.govt_service FROM tbl_applicant LEFT JOIN tbl_career ON tbl_applicant.id = tbl_career.applicant_id WHERE tbl_applicant.id = '{$data}' ORDER BY tbl_career.inclusive_fromdate DESC");
		print_r(json_encode($q));
	}

	if (isset($_GET['get-applicantJobs'])){/**/
		$data = $_POST['data'];
		$q = $Functions->PDO("SELECT * FROM tbl_personalinfo LEFT JOIN tbl_application ON tbl_personalinfo.id = tbl_application.applicant_id LEFT JOIN tbl_vacancies ON tbl_vacancies.id = tbl_application.vacancy_id WHERE tbl_personalinfo.id = '{$data}'  ORDER BY tbl_application.date DESC");
		print_r(json_encode($q));
	}

	if (isset($_GET['get-accountslist'])){/**/
		$data = $_POST['data'];
		$q = $Functions->PDO("SELECT * FROM tbl_businessManagers WHERE business_id = '{$data}'");

		print_r(json_encode($q));
	}

	if (isset($_GET['get-jobslist'])){/**/
		$data = $_POST['data'];
		$q = $Functions->PDO("SELECT * FROM tbl_vacancies WHERE business_id = '{$data}'");
		print_r(json_encode($q));
	}

	if (isset($_GET['get-applicantList'])){/**/
		$q = $Functions->PDO("SELECT tbl_applicant.id, tbl_personalinfo.family_name, tbl_personalinfo.given_name, tbl_personalinfo.picture FROM tbl_applicant LEFT JOIN tbl_personalinfo ON tbl_applicant.id = tbl_personalinfo.id ORDER BY tbl_applicant.id");
		print_r(json_encode($q));
	}

	if(isset($_GET['get-logs'])){ /**/
		$data = $_POST['data'];
		$result = [];
		$user = $data[0];
		$min = $data[1];
		$max = $data[2];
		if($user == 'admin'){
			$qEmployer = $Functions->PDO("SELECT tbl_businessmanagers.name, tbl_logs.remarks, tbl_vacancies.job_title, tbl_logs.date FROM tbl_logs LEFT JOIN tbl_vacancies ON tbl_logs.to_account_id = tbl_vacancies.id LEFT JOIN tbl_businessmanagers ON tbl_logs.from_account_id = tbl_businessmanagers.id WHERE to_account_id IN ( SELECT id FROM tbl_vacancies ) AND from_account_id IN (SELECT id FROM tbl_businessmanagers) ORDER BY `date` DESC LIMIT {$min},{$max}");
			$qApplicant = $Functions->PDO("SELECT tbl_personalinfo.given_name, tbl_logs.remarks, tbl_logs.date FROM tbl_logs LEFT JOIN tbl_personalinfo ON tbl_logs.to_account_id = tbl_personalinfo.id WHERE to_account_id IN ( SELECT id FROM tbl_personalinfo ) AND from_account_id IN (SELECT id FROM tbl_personalinfo) ORDER BY `date` DESC LIMIT {$min},{$max}");
			print_r(json_encode([$qEmployer,$qApplicant]));
		}
		else if($user == 'employer'){
			$qApplicant = $Functions->PDO("SELECT tbl_personalinfo.given_name, tbl_logs.remarks, tbl_logs.date FROM tbl_logs LEFT JOIN tbl_personalinfo ON tbl_logs.to_account_id = tbl_personalinfo.id LEFT JOIN tbl_vacancies ON tbl_logs.to_account_id = tbl_vacancies.id ORDER BY `date` DESC LIMIT {$min},{$max}");
			// $qApplication = $Functions->PDO("SELECT tbl_personalinfo.id, tbl_personalinfo.given_name, tbl_personalinfo.middle_name, tbl_personalinfo.family_name, tbl_skills.skill, tbl_personalinfo.picture, tbl_vacancies.id, tbl_vacancies.job_title, tbl_vacancies.skills FROM `tbl_logs` LEFT JOIN tbl_personalinfo ON tbl_logs.from_account_id = tbl_personalinfo.id LEFT JOIN tbl_vacancies ON tbl_logs.to_account_id = tbl_vacancies.id LEFT JOIN tbl_skills ON tbl_personalinfo.id = tbl_skills.applicant_id");
			print_r(json_encode($qApplicant));
		}
	}

	if(isset($_GET['do-updateInfo'])){/**/
		$data = $_POST['data'];
		if($data[0] == 'admin'){
			if($data[1] == 'name'){
				$field1 = $Functions->escape($data[3]);
				$field2 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[1]} to {$data[3]} {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_admin SET fname = {$field1}, lname = {$field2} WHERE id = '{$data[2]}'");
			}
			else if($data[1] == 'username'){
				$field1 = $Functions->escape($data[3]);
				$remarks = "Updated {$data[1]} to {$data[3]}";
				$q = $Functions->PDO("UPDATE tbl_admin SET username = {$field1} WHERE id = '{$data[2]}'");
				$_SESSION['kareer7836'][1] = $field1;
			}
			else if($data[1] == 'password'){
				$field1 = $Functions->password($data[3]);
				$remarks = "Updated {$data[1]}";
				$q = $Functions->PDO("UPDATE tbl_admin SET password = '{$field1}' WHERE id = '{$data[2]}'");
				$_SESSION['kareer7836'][1] = $field1;
			}
			else{
				$q = $Functions->PDO("");
			}

			if($q->execute()){
				$log = $Functions->log($data[2],$data[2],$remarks,'Update');
				echo 1;
			}
			else{
				$Data = $q->errorInfo();
				print_r($Data);
			}
		}
		else if($data[1] == 'business'){
			if($data[2] == 'name'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[2]} to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_business SET company_name = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'number'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[2]} to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_business SET contact_number = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'email'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[2]} to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_business SET email = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'description'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[2]} to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_business SET description = {$field1} WHERE id = '{$data[3]}'");
			}
			else{
				$q = $Functions->PDO("");
			}
			
			if($q->execute()){
				$log = $Functions->log($data[0],$data[3],$remarks,'Update');
				echo 1;
			}
			else{
				$Data = $q->errorInfo();
				print_r($Data);
			}
		}
		else if($data[1] == 'employer'){
			// print($data);
			if($data[2] == 'status'){
				$field = ($data[4] == 'deactivate')?0:1;
				$remarks = "{$data[5]}. Updated {$data[2]} to {$field}";
				$q = $Functions->PDO("UPDATE tbl_businessmanagers SET status = '{$field}' WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'name'){
				$field1 = $Functions->escape($data[3]);
				$remarks = "Updated {$data[2]} to {$data[3]}";
				$q = $Functions->PDO("UPDATE tbl_businessmanagers SET name = {$field1} WHERE id = '{$data[0]}'");
			}
			else if($data[2] == 'username'){
				$field1 = $Functions->escape($data[3]);
				$remarks = "Updated {$data[2]} to {$data[3]}";
				$q = $Functions->PDO("UPDATE tbl_businessmanagers SET email = {$field1} WHERE id = '{$data[0]}'");	
			}
			else if($data[2] == 'password'){
				$field1 = $Functions->password($data[3]);
				$remarks = "{$data[1]} is updated";
				$header = 'Update';
				$q = $Functions->PDO("UPDATE tbl_businessmanagers SET password = '{$field1}' WHERE id = '{$data[0]}'");
				$_SESSION['kareer7836'][1] = $field1;
			}
			else{
				$q = $Functions->PDO("");
			}
			if($q->execute()){
				$log = $Functions->log($data[0],($data[2] == 'status')?$data[3]:$data[0],$remarks,'Update');
				echo 1;
			}
			else{
				$Data = $q->errorInfo();
				print_r($Data);
			}
		}
		else if($data[1] == 'applicant'){
			if($data[2] == 'status'){
				$field = ($data[4] == 'deactivate')?0:1;
				$remarks = "{$data[5]}. Updated {$data[2]} to {$field}";
				$q = $Functions->PDO("UPDATE tbl_applicant SET status = '{$field}' WHERE id = '{$data[3]}'");
			}
			else{
				$q = $Functions->PDO("");
			}

			if($q->execute()){
				$log = $Functions->log($data[0],$data[3],$remarks,"Update");
				echo 1;
			}
			else{
				$Data = $q->errorInfo();
				print_r($Data);
			}
		}
		else if($data[1] == 'job'){
			// print_r($data);
			if($data[2] == 'title'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[2]} to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_vacancies SET job_title = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'skills'){
				$field1 = json_encode($data[4]);
				$remarks = "Updated {$data[2]} to {$field1}";
				$q = $Functions->PDO("UPDATE tbl_vacancies SET skills = '{$field1}' WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'salary'){
				$field1 = $Functions->escape($data[4]);
				$field2 = $Functions->escape($data[5]);
				$remarks = "Updated {$data[2]} to {$data[4]} - {$data[5]}";
				$q = $Functions->PDO("UPDATE tbl_vacancies SET salary_min = {$field1}, salary_max = {$field2} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'date'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[2]} to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_vacancies SET vacancy_date = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'shortDes'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated Short Description to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_vacancies SET short_description = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'longDes'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated Description to {$data[4]}";
				$q = $Functions->PDO("UPDATE tbl_vacancies SET description = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'status'){
				$field1 = $Functions->escape($data[4]);
				$remarks = "Updated {$data[2]} to {$data[4]}. {$data[5]}";
				$q = $Functions->PDO("UPDATE tbl_vacancies SET status = {$field1} WHERE id = '{$data[3]}'");
			}
			else if($data[2] == 'delete'){
				$remarks = "Deleted. {$data[4]}";
				$q = $Functions->PDO("DELETE FROM tbl_vacancies WHERE id = '{$data[3]}'");
			}
			else{
				$q = $Functions->PDO("");
			}
			if($q->execute()){
				$log = $Functions->log($data[0],$data[3],$remarks,"Update");
				echo 1;
			}
			else{
				$Data = $q->errorInfo();
				print_r($Data);
			}
		}
		else{
			echo 0;
		}
	}

	if(isset($_GET['do-updateImage'])){/**/
		if(isset($_POST['data'])){
			$data = $_POST['data'];
			// print_r($data);
			$date = new DateTime();
			$time = $date->getTimestamp();
			if($data[0] == 'business'){
				$q = $Functions->PDO("SELECT * FROM tbl_business WHERE id = '{$data[2]}'");
				$_filename = ($q[0][5] == "")?"business.png":$q[0][5];
				if(file_exists("../images/logo/{$_filename}")){
					$filename = "../images/logo/{$_filename}";
				}
				else{
					$_filename = "{$data[0]}_{$time}.rnr";
					$filename = "../images/logo/{$_filename}";
				}			
				$picture = $Functions->saveImage($filename,$data[3]);
				$q = $Functions->PDO("UPDATE tbl_business SET image = '{$_filename}' WHERE id = '{$data[2]}'");
			}
			else if($data[0] == 'admin'){
				$q = $Functions->PDO("SELECT * FROM tbl_admin WHERE id = '{$data[2]}'");
				$_filename = ($q[0][3] == "")?"admin.png":$q[0][3];
				if(file_exists("../images/profile/{$_filename}")){
					$filename = "../images/profile/{$_filename}";
				}
				else{
					$_filename = "{$data[0]}_{$time}.rnr";
					$filename = "../images/profile/{$_filename}";
				}
				$picture = $Functions->saveImage($filename,$data[3]);
				$q = $Functions->PDO("UPDATE tbl_admin SET image = '{$_filename}' WHERE id = '{$data[2]}'");
			}
			else if($data[0] == 'employer'){
				$q = $Functions->PDO("SELECT * FROM tbl_businessmanagers WHERE id = '{$data[2]}'");
				$_filename = ($q[0][3] == "")?"admin.png":$q[0][3];
				if(file_exists("../images/profile/{$_filename}")){
					$filename = "../images/profile/{$_filename}";
				}
				else{
					$_filename = "{$data[0]}_{$time}.rnr";
					$filename = "../images/profile/{$_filename}";
				}
				$picture = $Functions->saveImage($filename,$data[3]);
				$q = $Functions->PDO("UPDATE tbl_businessmanagers SET picture = '{$_filename}' WHERE id = '{$data[2]}'");
			}
			else{
				$q = $Functions->PDO("");
			}
			if($q->execute()){
				$log = $Functions->log(($data[0] =='business')?'admin_id':$data[2],$data[2],'update picture','Update');
				echo 1;
			}
			else{
				$Data = $q->errorInfo();
				print_r($Data);
			}
		}
		else{
			echo "Hacker";
		}
	}

	if(isset($_GET['get-Applications'])){ /**/
		if(isset($_POST["data"])){
			$data = $_POST['data'];
			$result = [];
			$Query = $Functions->PDO_SQL("SELECT * FROM tbl_application WHERE applicant = '{$data}'");
			foreach ($Query as $key => $value) {
					$QueryVacancy = $Functions->PDO_SQL("SELECT * FROM tbl_vacancies WHERE id = '{$value[1]}'");
					$QueryEmployer = $Functions->PDO_SQL("SELECT * FROM tbl_employer WHERE id = '{$QueryVacancy[0][1]}'");
					$QueryApplicant = $Functions->PDO_SQL("SELECT * FROM tbl_applicant WHERE id = '{$value[2]}'");
					$result[] = [$value,$QueryEmployer[0],$QueryVacancy[0],$QueryApplicant[0]];
			}
			print_r(json_encode($result));
		}
		else{
			echo "Hacker";
		}
	}

	if (isset($_GET['do-postJob'])) { /**/
		$data = $_POST['data'];
		$id = $Functions->PDO_IDGenerator('tbl_vacancies','id');
		$date = $Functions->PDO_DateAndTime();
		$skills = json_encode($data[8]);
		$query = $Functions->PDO("INSERT INTO tbl_vacancies(id,employer_id,business_id,short_description,description,vacancy_date,job_title,skills,salary_min,salary_max,date,status) VALUES('{$id}','{$data[0]}','{$data[1]}','{$data[6]}','{$data[7]}','{$data[5]}','{$data[2]}','$skills','{$data[3]}','{$data[4]}','{$date}',1)");
		if($query->execute()){
			$log = $Functions->log($data[0],$id,'Posted a job','Add');
			echo 1;
		}
		else{
			$Data = $query->errorInfo();
			print_r($Data);
		}
	}    

	if(isset($_GET['do-addBusiness'])){/**/
        $id = $Functions->PDO_IDGenerator('tbl_business','id');
		$date = $Functions->PDO_DateAndTime();
		$data = $_POST['data'];

		$name = $Functions->escape($data[1]);
		$number = $Functions->escape($data[2]);
		$email = $Functions->escape($data[3]);
		$address = $Functions->escape($data[4]);

		$query = $Functions->PDO("INSERT INTO tbl_business(id,company_name,contact_number,email,address,status,`date`) 
			VALUES ('{$id}',{$name},{$number},{$email},{$address},'1','{$date}')");
		if($query->execute()){
			$log = $Functions->log($data[0],$id,"Added {$data[1]} to businesses",'Add');
			echo 1;
		}
		else{
			$Data = $query->errorInfo();
			print_r($Data);
		}
	}

	if(isset($_GET['do-addBusinessAccount'])){/**/
        $id = $Functions->PDO_IDGenerator('tbl_businessManagers','id');
		$date = $Functions->PDO_DateAndTime();
		$data = $_POST['data'];
		$business_id = $Functions->escape($data[1]);
		$name = $Functions->escape($data[2]);
		$position = $Functions->escape($data[3]);
		$email = $Functions->escape($data[4]);
		$password = $Functions->password($data[5]);

		$query = $Functions->PDO("INSERT INTO tbl_businessManagers(id,business_id,name,email,position,password,status,`date`) VALUES('{$id}',{$business_id},{$name},{$email},{$position},'{$password}','1','{$date}')");
		if($query->execute()){
			$log = $Functions->log($data[0],$id,"Added {$data[2]} as account manager",'Add');
			echo 1;
		}
		else{
			$Data = $query->errorInfo();
			print_r($Data);
		}
	}

	if(isset($_GET['do-backup'])){
		$q = $Functions->db_buckup();
		print_r($q);
	}

	if(isset($_GET['set-full'])){/*c*/
		$data = $_POST['data'];

		$q = $Functions->PDO("UPDATE tbl_vacancies SET status = 0 WHERE id = '{$data}'");
		if($q->execute()){
			echo 1;
		}
		else{
			$Data = $q->errorInfo();
			print_r($Data);
		}
	}

	if(isset($_GET['set-active'])){/*c*/
		$data = $_POST['data'];

		$q = $Functions->PDO("UPDATE tbl_vacancies SET status = 1 WHERE id = '{$data}'");
		if($q->execute()){
			echo 1;
		}
		else{
			$Data = $q->errorInfo();
			print_r($Data);
		}
	}

	if(isset($_GET['set-pending'])){/*c*/
		$data = $_POST['data'];

		$q = $Functions->PDO("UPDATE tbl_vacancies SET status = 2 WHERE id = '{$data}'");
		if($q->execute()){
			echo 1;
		}
		else{
			$Data = $q->errorInfo();
			print_r($Data);
		}
	}
?>