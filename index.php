<?php
ob_start();
session_start();
if(isset($_SESSION['name'])) {
	header("Location: home.php");
	die();
}
error_reporting(E_ALL ^ E_WARNING); 
require_once './globaldata.php';
?>
<!DOCTYPE html>
<html>
<head>
	<!--favicon-->
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="theme-color" content="#ffffff">
	<!--endfavicon-->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Go Online</title>
	<script src="./js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<script type="text/javascript" src="./js/eye.js"></script>
	<script>
		$(function () {
  			$('[data-toggle="tooltip"]').tooltip()
		});
	</script>
	<?php
		$msgusex = "";
	?>
	<div id="header">
		<h1><img src="./icons/grid.svg" height="30" width="30">&nbsp;Go<a class="text-decoration-none" href='https://github.com/marijadebe/goapp-dev' style="color:white;font-size:30px;"><i class="fa fa-github fa-sm" aria-hidden="true" style="float:right;vertical-align: middle;line-height: inherit;padding-right:5px;"></i></a></h1>
	</div><br/>
	<div id="content">
		<div class="box"><img src="./icons/lock.svg">&nbsp;Register<br/><br/>
			<form method="post" action="" id="registration">
				Username:
				<input type="text" name="username" class="form-control form-control-sm" id="reguser">
				<span class="invalid-feedback" id="reguserfeed" style="display:none;"></span><br/>
				Password: 
				<div class="input-group mb-3" style="margin-bottom:0!important;">
					<input type="password" name="password" data-toggle="tooltip" data-placement="bottom" title="At least 7 characters." class="form-control form-control-sm" id="regpass">
					<span class="input-group-append">
	    				<span class="input-group-text" type="button"><i onclick="oko(0)" class="fa fa-eye-slash" type="button"></i></span>
	  				</span>
				</div>
				<div class="invalid-feedback" id="regpassfeed" style="display:none;"></div><br/>
				Repeat Password: 
				<div class="input-group mb-3" style="margin-bottom:0!important;">
					<input type="password" name="passwordagain" class="form-control form-control-sm" id="regrep">
					<span class="input-group-append">
	    				<span class="input-group-text"><i onclick="oko(1)" class="fa fa-eye-slash" type="button"></i></span>
	  				</span>
				</div>
				<div class="invalid-feedback" id="regrepfeed" style="display:none;"></div><br/>
				Country: 
				<select id="country" name="country" name="country" class="custom-select custom-select-sm">
					<optgroup label="Europe">
						<option value="Albania">Albania</option>
						<option value="Andorra">Andorra</option>
						<option value="Austria">Austria</option>
						<option value="Belarus">Belarus</option>
						<option value="Belgium">Belgium</option>
						<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
						<option value="Bulgaria">Bulgaria</option>
						<option value="Croatia">Croatia</option>
						<option value="Cyprus">Cyprus</option>
						<option value="Czech Republic">Czech Republic</option>
						<option value="Denmark">Denmark</option>
						<option value="Estonia">Estonia</option>
						<option value="Finland">Finland</option>
						<option value="France">France</option>
						<option value="Germany">Germany</option>
						<option value="Greece">Greece</option>
						<option value="Hungary">Hungary</option>
						<option value="Iceland">Iceland</option>
						<option value="Ireland">Ireland</option>
						<option value="Italy">Italy</option>
						<option value="Kosovo">Kosovo</option>
						<option value="Latvia">Latvia</option>
						<option value="Lichtenstein">Lichtenstein</option>
						<option value="Lithuania">Lithunia</option>
						<option value="Luxembourg">Luxembourg</option>
						<option value="Malta">Malta</option>
						<option value="Moldova">Moldova</option>
						<option value="Monaco">Monaco</option>
						<option value="Montenegro">Montenegro</option>
						<option value="Netherlands">Netherlands</option>
						<option value="North Macedonia">North Macedonia</option>
						<option value="Norway">Norway</option>
						<option value="Poland">Poland</option>
						<option value="Portugal">Portugal</option>
						<option value="Romania">Romania</option>
						<option value="Russia">Russia</option>
						<option value="San Marino">San Marino</option>
						<option value="Serbia">Serbia</option>
						<option value="Slovakia">Slovakia</option>
						<option value="Slovenia">Slovenia</option>
						<option value="Spain">Spain</option>
						<option value="Sweden">Sweden</option>
						<option value="Switzerland">Switzerland</option>
						<option value="Turkey">Turkey</option>
						<option value="Ukraine">Ukraine</option>
						<option value="United Kingdom">United Kingdom</option>
						<option value="Vatican City">Vatican City</option>
					</optgroup>
					<optgroup label="North America">
						<option value="Antigua and Barbuda">Antigua and Barbuda</option>
						<option value="Bahamas">Bahamas</option>
						<option value="Barbados">Barbados</option>
						<option value="Belize">Belize</option>
						<option value="Canada">Canada</option>
						<option value="Costa Rica">Costa Rica</option>
						<option value="Cuba">Cuba</option>
						<option value="Dominica">Dominica</option>
						<option value="Dominican Republic">Dominican Republic</option>
						<option value="El Salvador">El Salvador</option>
						<option value="Grenada">Grenada</option>
						<option value="Guatemala">Guatemala</option>
						<option value="Haiti">Haiti</option>
						<option value="Honduras">Honduras</option>
						<option value="Jamaica">Jamaica</option>
						<option value="Mexico">Mexico</option>
						<option value="Nicaragua">Nicaragua</option>
						<option value="Panama">Panama</option>
						<option value="St. Kitts and Nevis">St. Kitts and Nevis</option>
						<option value="St. Lucia">St. Lucia</option>
						<option value="St. Vincent and the Grenadines">St. Vincent and the Grenadines</option>
						<option value="Trinidad and Tobago">Trinidad and Tobago</option>
						<option value="United States">United States</option>
					</optgroup>
					<optgroup label="South America">
						<option value="Argentina">Argentina</option>
						<option value="Bolivia">Bolivia</option>
						<option value="Brazil">Brazil</option>
						<option value="Chile">Chile</option>
						<option value="Colombia">Colombia</option>
						<option value="Ecuador">Ecuador</option>
						<option value="Guyana">Guyana</option>
						<option value="Paraguay">Paraguay</option>
						<option value="Peru">Peru</option>
						<option value="Suriname">Suriname</option>
						<option value="Uruguay">Uruguay</option>
						<option value="Venezuela">Venezuela</option>
					</optgroup>	
					<optgroup label="Asia">
						<option value="Afghanistan">Afghanistan</option>
						<option value="Armenia">Armenia</option>
						<option value="Azerbaijan">Azerbaijan</option>
						<option value="Bahrain">Bahrain</option>
						<option value="Bangladesh">Bangladesh</option>
						<option value="Bhutan">Bhutan</option>
						<option value="Brunei">Brunei</option>
						<option value="Cambodia">Cambodia</option>
						<option value="China">China</option>
						<option value="Egypt">Egypt</option>
						<option value="Georgia">Georgia</option>
						<option value="India">India</option>
						<option value="Indonesia">Indonesia</option>
						<option value="Iran">Iran</option>
						<option value="Iraq">Iraq</option>
						<option value="Israel">Israel</option>
						<option value="Japan">Japan</option>
						<option value="Jordan">Jordan</option>
						<option value="Kazachstan">Kazachstan</option>
						<option value="Kuwait">Kuwait</option>
						<option value="Kyrgyzstan">Kyrgyzstan</option>
						<option value="Laos">Laos</option>
						<option value="Lebanon">Lebanon</option>
						<option value="Malaysia">Malaysia</option>
						<option value="Maldives">Maldives</option>
						<option value="Mongolia">Mongolia</option>
						<option value="Myanmar">Myanmar</option>
						<option value="Nepal">Nepal</option>
						<option value="North Korea">North Korea</option>
						<option value="Oman">Oman</option>
						<option value="Pakistan">Pakistan</option>
						<option value="Philippines">Philippines</option>
						<option value="Qatar">Qatar</option>
						<option value="Saudi Arabia">Saudi Arabia</option>
						<option value="Singapore">Singapore</option>
						<option value="South Korea">South Korea</option>
						<option value="Sri Lanka">Sri Lanka</option>
						<option value="Syria">Syria</option>
						<option value="Tajikistan">Tajikistan</option>
						<option value="Thailand">Thailand</option>
						<option value="East Timor">East Timor</option>
						<option value="Turkmenistan">Turkmenistan</option>
						<option value="United Arab Emirates">United Arab Emirates</option>
						<option value="Uzbekistan">Uzbekistan</option>
						<option value="Vietnam">Vietnam</option>
						<option value="Yemen">Yemen</option>
					</optgroup>
					<optgroup label="Africa">
						<option value="Algeria">Algeria</option>
						<option value="Angola">Angola</option>
						<option value="Benin">Benin</option>
						<option value="Botswana">Botswana</option>
						<option value="Burkina Faso">Burkina Faso</option>
						<option value="Burundi">Burundi</option>
						<option value="Cameroon">Cameroon</option>
						<option value="Cape Verde">Cape Verde</option>
						<option value="Central African Republic">Central Africa Republic</option>
						<option value="Chad">Chad</option>
						<option value="Comoros">Comoros</option>
						<option value="DR Congo">DR Congo</option>
						<option value="Djibouti">Djibouti</option>
						<option value="Egypt">Egypt</option>
						<option value="Equatorial Guinea">Equatorial Guinea</option>
						<option value="Eritrea">Eritrea</option>
						<option value="Eswatini">Eswatini</option>
						<option value="Ethiopia">Ethiopia</option>
						<option value="Gabon">Gabon</option>
						<option value="Gambia">Gambia</option>
						<option value="Ghana">Ghana</option>
						<option value="Guinea">Guinea</option>
						<option value="Guinea-Bissau">Guinea-Bissau</option>
						<option value="Ivory Coast">Ivory Coast</option>
						<option value="Kenya">Kenya</option>
						<option value="Lesotho">Lesotho</option>
						<option value="Liberia">Liberia</option>
						<option value="Libya">Libya</option>
						<option value="Madagascar">Madagascar</option>
						<option value="Malawi">Malawi</option>
						<option value="Mali">Mali</option>
						<option value="Mauritania">Mauritania</option>
						<option value="Mauritius">Mauritius</option>
						<option value="Mozambique">Mozambique</option>
						<option value="Namibia">Namibia</option>
						<option value="Niger">Niger</option>
						<option value="Nigeria">Nigeria</option>
						<option value="Congo">Congo</option>
						<option value="Kenya">Kenya</option>
						<option value="Rwanda">Rwanda</option>
						<option value="Sao Tome and Principe">Sao Tome and Principe</option>
						<option value="Senegal">Senegal</option>
						<option value="Seychelles">Seychelles</option>
						<option value="Sierra Leone">Sierra Leone</option>
						<option value="Somalia">Somalia</option>
						<option value="South Africa">South Africa</option>
						<option value="South Sudan">South Sudan</option>
						<option value="Sudan">Sudan</option>
						<option value="Tanzania">Tanzania</option>
						<option value="Togo">Togo</option>
						<option value="Tunisia">Tunisia</option>
						<option value="Uganda">Uganda</option>
						<option value="Zambia">Zambia</option>
						<option value="Zimbabwe">Zimbabwe</option>
					</optgroup>
					<optgroup label="Oceania">
						<option value="Australia">Australia</option>
						<option value="Fiji">Fiji</option>
						<option value="Kiribati">Kiribati</option>
						<option value="Marshall Islands">Marshall Islands</option>
						<option value="Micronesia">Micronesia</option>
						<option value="Nauru">Nauru</option>
						<option value="New Zealand">New Zealand</option>
						<option value="Palau">Palau</option>
						<option value="Papua New Guinea">Iran</option>
						<option value="Samoa">Samoa</option>
						<option value="Solomon Islands">Solomon Islands</option>
						<option value="Tonga">Tonga</option>
						<option value="Tuvalu">Tuvalu</option>
						<option value="Vanuatu">Vanuatu</option>
					</optgroup>
				</select><br/><br/>
				<input type="submit" value="Register" class="btn btn-primary" name="regbut">
				<?php
					if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['passwordagain']) && isset($_POST['country']) && ($_POST['password'] == $_POST['passwordagain']) && isset($_POST['regbut'])) {
						if(strlen($_POST['password']) > 6) {
							$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
							if ($conn->connect_error) {
		  						$msgusex = "<div id='erroralert' class='alert alert-danger' role='alert'>Connection error.</div><script>$('#erroralert').fadeOut(4000);</script>";
							}else {
								$username = $conn->real_escape_string(strip_tags($_POST['username']));
								$password = md5($_POST['password']);
								$passwordagain = md5($_POST['passwordagain']);
								$country = $conn->real_escape_string($_POST['country']);
								$sql = "SELECT prezdivka FROM hraci WHERE prezdivka='$username'";
								$result = $conn->query($sql);
								if ($result->num_rows > 0) {
									$msgusex = "<div id='erroralert' class='alert alert-danger' role='alert'>This username already exists!</div><script>$('#erroralert').fadeOut(4000);</script>";
									$conn->close();
								}else {
									$sql = "INSERT INTO hraci (prezdivka,heslo,rating,zeme,online) VALUES ('$username', '$password', '1200', '$country', '1')";
									if ($conn->query($sql) === FALSE) {
										echo "Error: " . $sql . "<br>" . $conn->error;
									}
									$_SESSION['name'] = $username;
									$conn->close();
									header("Location: home.php");
									die();
								}
							}
						}else {
							$msgusex = "<div id='erroralert' class='alert alert-danger' role='alert'>Your password has to be atleast 7 characters long.</div><script>$('#erroralert').fadeOut(4000);</script>";
						}
					}
				?>
			</form>
		</div>
		<div class="box" style="width:300px;"><img src="./icons/key.svg">&nbsp;Login<br/><br/>
			<form action="" method="post" id="login">
				Username:
				<input type="text" name="logname" class="form-control form-control-sm" id="loguser">
				<span class="invalid-feedback" id="loguserfeed"></span><br/>
				Password: 
				<div class="input-group mb-3" style="margin-bottom:0!important;">
					<input type="password" name="logpassword" class="form-control form-control-sm" id="logpass">
					<span class="input-group-append">
	    				<span class="input-group-text"><i onclick="oko(2)" class="fa fa-eye-slash" type="button"></i></span>
	  				</span>
				</div>
				<div class="invalid-feedback" id="logpassfeed"></div><br/>
				<input type="submit" value="Login" class="btn btn-primary">
			<?php
				if(isset($_POST['logname']) && isset($_POST['logpassword'])) {
					$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
					$logname = $conn->real_escape_string(strip_tags($_POST['logname']));
					$logpassword = md5($_POST['logpassword']);
					if ($conn->connect_error) {
	  					die("Connection failed: " . $conn->connect_error);
					}
					$sql = "SELECT prezdivka,heslo FROM hraci WHERE prezdivka='$logname' AND heslo='$logpassword'";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						$_SESSION['name'] = $logname;
						$conn->close();
						header("Location: home.php");
						die();
					}else {
						$msgusex = "<div id='erroralert' class='alert alert-danger' role='alert'>No account by that name and/or of that password exists.</div><script>$('#erroralert').fadeOut(4000);</script>";
						$conn->close();
					}
				}
			?>

			</form>
		</div>
		<script type="text/javascript" src="./js/indexvalidation.js"></script>
		<div class="box"><img src="./icons/person.svg">&nbsp;Players<br/><br/>
			Active:&nbsp;<?php
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
	  				die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT COUNT(*) FROM hraci WHERE online=1";
				$result = $conn->query($sql);
				$conn->close();
				$result = $result->fetch_assoc();
				$result2 = reset($result); 
				echo $result2;
			?><br/>
			Total:&nbsp;<?php
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
	  				die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT COUNT(*) FROM hraci";
				$result = $conn->query($sql);
				$conn->close();
				$result = $result->fetch_assoc();
				$result2 = reset($result); 
				echo $result2;
			?><br/>
			Games played:&nbsp;<?php
				$conn = new mysqli(g_SERVER, g_USERNAME, g_PASSWORD, g_DB);
				if ($conn->connect_error) {
	  				die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT gp FROM hraci";
				$result = $conn->query($sql);
				$vysledek = 0;
				if($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$vysledek += $row['gp'];
					}
					echo $vysledek;
				}else {
					echo "0";
				}
			?><br/>
		</div>
		<div class="box"><img src="./icons/question-circle.svg">&nbsp;What is Go?<br/><br/>
			Go is an abstract board game for two players. Both players are competing to surround more territory whilst restricting their opponents influence.<br/>Learn More: <a href="https://en.wikipedia.org/wiki/Go_(game)">https://en.wikipedia.org/wiki/Go_(game)</a>
		</div>
	</div>
	<div id="errors"><br/>
		<?php
			echo "<br/>".$msgusex;
		?>
	</div>
</body>
</html>
<?php
	ob_flush();
?>