<style>
 	body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
	table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
	img { -ms-interpolation-mode: bicubic; }
	img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
	table { border-collapse: collapse !important; }
	body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
	a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }
	div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
<body style="background-color: #f7f5fa; margin: 0 !important; padding: 0 !important;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td bgcolor="#3441a4" align="center">
				<table border="0" cellpadding="0" cellspacing="0" width="480" >
					<tr>
						<td align="center" valign="top" style="padding: 40px 10px 40px 10px;">
							<div style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 18px;" border="0"><img src="https://premierconsumer.org/wp-content/uploads/2021/09/logo.jpg" style="width: 55%;">
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#3441a4" align="center" style="padding: 0px 10px 0px 10px;">
				<table border="0" cellpadding="0" cellspacing="0" width="480" >
					<tr>
						<td bgcolor="#ffffff" align="left" valign="top" style="padding: 30px 30px 20px 30px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; line-height: 48px;">
							<h1 style="font-size: 28px; font-weight: 400; margin: 0;text-align: center;">A New User has registered</h1>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
				<table border="0" cellpadding="0" cellspacing="0" width="480" >
					<tr>
						<td bgcolor="#ffffff" align="left">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="2" style="padding-left:30px;padding-right:15px;padding-bottom:10px; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;">
										<p><strong>%user_name%</strong> has just created an account and registered in <strong>%program% </strong>Program.</p>
										<p><strong>User Email:</strong> %user_email%
										<br><strong>User Name:</strong> %user_name%
										<br><strong>Phone Number:</strong> %user_phone%
										<br><strong>Number of person/household:</strong> %user_household%
										<br><strong>Yearly Income:</strong> %user_income%
										<br><strong>Course Reason:</strong> %course_reason%
										<br><strong>Bankruptcy Number:</strong> %bnumber%
										<?php if(isset($sp_data)){ 
										?>
										<br><strong>Spouse Name:</strong> %sname%
										<br><strong>Spouse Phone Number:</strong> %sphone%
										<br><strong>Spouse Email:</strong> %semail%
										<?php
										} ?>
										<br><strong>Purchased Plan:</strong> %p_plan%</p>
										<p>Thanks & Regards<br/>Premier Consumer Team</p>
										<br>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;"> <table border="0" cellpadding="0" cellspacing="0" width="480">
				<tr>
					<tr>
						<td style="text-align:center;margin-top:5px;">
							<p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>premierconsumer.org</strong> </p>
						</td>
					</tr>
				</tr>
			</td>
		</tr>
	</table>
</body>