<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Contact Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
				Contact Description<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>
	<div id="collapse_1" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Business

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Name".',') !== FALSE) { echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."First Name".',') !== FALSE) { echo " checked"; } ?> value="First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Last Name".',') !== FALSE) { echo " checked"; } ?> value="Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Preferred Name".',') !== FALSE) { echo " checked"; } ?> value="Preferred Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Preferred Name

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Role".',') !== FALSE) { echo " checked"; } ?> value="Role" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Security Level

            <input type="checkbox" <?php if (strpos($contacts_config, ','."Region".',') !== FALSE) { echo " checked"; } ?> value="Region" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Region

            <input type="checkbox" <?php if (strpos($contacts_config, ','."Classification".',') !== FALSE) { echo " checked"; } ?> value="Classification" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Classification

            <input type="checkbox" <?php if (strpos($contacts_config, ','."Division".',') !== FALSE) { echo " checked"; } ?> value="Division" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Division

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Name on Account".',') !== FALSE) { echo " checked"; } ?> value="Name on Account" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Name on Account
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Operating As".',') !== FALSE) { echo " checked"; } ?> value="Operating As" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Operating As
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Emergency Contact".',') !== FALSE) { echo " checked"; } ?> value="Emergency Contact" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Emergency Contact

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Occupation".',') !== FALSE) { echo " checked"; } ?> value="Occupation" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Occupation
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Office Phone".',') !== FALSE) { echo " checked"; } ?> value="Office Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Office Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Fax".',') !== FALSE) { echo " checked"; } ?> value="Fax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Fax
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Email Address".',') !== FALSE) { echo " checked"; } ?> value="Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Company Email Address".',') !== FALSE) { echo " checked"; } ?> value="Company Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Company Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Website".',') !== FALSE) { echo " checked"; } ?> value="Website" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Website
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Customer Address".',') !== FALSE) { echo " checked"; } ?> value="Customer Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Customer Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Application".',') !== FALSE) { echo " checked"; } ?> value="Upload Application" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Application
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Contact Image".',') !== FALSE) { echo " checked"; } ?> value="Contact Image" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Contact Image
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Description
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Contact Since".',') !== FALSE) { echo " checked"; } ?> value="Contact Since" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Contact Since
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Date of Last Contact".',') !== FALSE) { echo " checked"; } ?> value="Date of Last Contact" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Date of Last Contact
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Referred By".',') !== FALSE) { echo " checked"; } ?> value="Referred By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Referred By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Company".',') !== FALSE) { echo " checked"; } ?> value="Company" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Company
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Position".',') !== FALSE) { echo " checked"; } ?> value="Position" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Position
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Title

			<input type="checkbox" <?php if (strpos($contacts_config, ','."License #".',') !== FALSE) { echo " checked"; } ?> value="License #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Licence #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Credential ".',') !== FALSE) { echo " checked"; } ?> value="Credential " style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Credential
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Alberta Health Care No".',') !== FALSE) { echo " checked"; } ?> value="Alberta Health Care No" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Alberta Health Care #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."MVA".',') !== FALSE) { echo " checked"; } ?> value="MVA" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;MVA
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Maintenance Patient".',') !== FALSE) { echo " checked"; } ?> value="Maintenance Patient" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Maintenance Patient
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Invoice".',') !== FALSE) { echo " checked"; } ?> value="Invoice" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Invoice
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Tax Exemption".',') !== FALSE) { echo " checked"; } ?> value="Client Tax Exemption" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Client Tax Exemption

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Tax Exemption Number".',') !== FALSE) { echo " checked"; } ?> value="Tax Exemption Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Tax Exemption #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."AISH Card#".',') !== FALSE) { echo " checked"; } ?> value="AISH Card#" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;AISH Card#
			<input type="checkbox" <?php if (strpos($contacts_config, ','."BIO".',') !== FALSE) { echo " checked"; } ?> value="BIO" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;BIO
			<input type="checkbox" <?php if (strpos($contacts_config, ','."DUNS".',') !== FALSE) { echo " checked"; } ?> value="DUNS" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;DUNS
			<input type="checkbox" <?php if (strpos($contacts_config, ','."CAGE".',') !== FALSE) { echo " checked"; } ?> value="CAGE" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;CAGE
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Self Identification".',') !== FALSE) { echo " checked"; } ?> value="Self Identification" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Self Identification
			<input type="checkbox" <?php if (strpos($contacts_config, ','."SIN".',') !== FALSE) { echo " checked"; } ?> value="SIN" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;SIN
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Employee Number".',') !== FALSE) { echo " checked"; } ?> value="Employee Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Employee #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Credit Card on File".',') !== FALSE) { echo " checked"; } ?> value="Credit Card on File" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Credit Card on File
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Intake Form".',') !== FALSE) { echo " checked"; } ?> value="Intake Form" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Intake Form
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Show/Hide User".',') !== FALSE) { echo " checked"; } ?> value="Show/Hide User" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Show/Hide User

            <input type="checkbox" <?php if (strpos($contacts_config, ','."Nick Name".',') !== FALSE) { echo " checked"; } ?> value="Nick Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Nickname
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Profile Link".',') !== FALSE) { echo " checked"; } ?> value="Profile Link" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Profile Link

            <input type="checkbox" <?php if (strpos($contacts_config, ','."Assigned Staff".',') !== FALSE) { echo " checked"; } ?> value="Assigned Staff" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Assigned Staff
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Contact Category".',') !== FALSE) { echo " checked"; } ?> value="Contact Category" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Contact Category
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Gender".',') !== FALSE) { echo " checked"; } ?> value="Gender" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Gender
            <input type="checkbox" <?php if (strpos($contacts_config, ','."License".',') !== FALSE) { echo " checked"; } ?> value="License" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;License
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Insurer".',') !== FALSE) { echo " checked"; } ?> value="Insurer" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Insurer
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Scheduled Days/Hours".',') !== FALSE) { echo " checked"; } ?> value="Scheduled Days/Hours" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Scheduled Days/Hours


            <input type="checkbox" <?php if (strpos($contacts_config, ','."Correspondence Language".',') !== FALSE) { echo " checked"; } ?> value="Correspondence Language" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Correspondence Language
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Accepts to receive emails".',') !== FALSE) { echo " checked"; } ?> value="Accepts to receive emails" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Agrees To Receive Emails

            <input type="checkbox" <?php if (strpos($contacts_config, ','."Amount To Bill".',') !== FALSE) { echo " checked"; } ?> value="Amount To Bill" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Amount To Bill
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Amount Owing".',') !== FALSE) { echo " checked"; } ?> value="Amount Owing" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Amount Owing
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Amount Credit".',') !== FALSE) { echo " checked"; } ?> value="Amount Credit" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Amount To Credit
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Accounts Receivable Data".',') !== FALSE) { echo " checked"; } ?> value="Accounts Receivable Data" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Accounts Receivable Data
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Account History Data".',') !== FALSE) { echo " checked"; } ?> value="Account History Data" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Account History Data
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Patient Block Booking".',') !== FALSE) { echo " checked"; } ?> value="Patient Block Booking" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Patient Block Booking
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Extended Health Benefits".',') !== FALSE) { echo " checked"; } ?> value="Extended Health Benefits" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Extended Health Benefits

			<br><br>
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Rating".',') !== FALSE) { echo " checked"; } ?> value="Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Rating

			<br>
			Rating Colors
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bronze Rating".',') !== FALSE) { echo " checked"; } ?> value="Bronze Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bronze
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Silver Rating".',') !== FALSE) { echo " checked"; } ?> value="Silver Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Silver
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Gold Rating".',') !== FALSE) { echo " checked"; } ?> value="Gold Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Gold
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Platinum Rating".',') !== FALSE) { echo " checked"; } ?> value="Platinum Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Platinum
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Diamond Rating".',') !== FALSE) { echo " checked"; } ?> value="Diamond Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Diamond
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Green Rating".',') !== FALSE) { echo " checked"; } ?> value="Green Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Green
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Yellow Rating".',') !== FALSE) { echo " checked"; } ?> value="Yellow Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Yellow
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Light blue Rating".',') !== FALSE) { echo " checked"; } ?> value="Light blue Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Light blue
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dark blue Rating".',') !== FALSE) { echo " checked"; } ?> value="Dark blue Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Dark blue
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Red Rating".',') !== FALSE) { echo " checked"; } ?> value="Red Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Red
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Pink Rating".',') !== FALSE) { echo " checked"; } ?> value="Pink Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Pink
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Purple Rating".',') !== FALSE) { echo " checked"; } ?> value="Purple Rating" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Purple

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field"	value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Site Information."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_site" >
				Site Information<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>
	<div id="collapse_site" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Customer(Client/Customer/Business)".',') !== FALSE) { echo " checked"; } ?> value="Customer(Client/Customer/Business)" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Customer(Client/Customer/Business)

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Site Name (Location)".',') !== FALSE) { echo " checked"; } ?> value="Site Name (Location)" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Site Name (Location)
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Display Name".',') !== FALSE) { echo " checked"; } ?> value="Display Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Display Name

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Business Sites".',') !== FALSE) { echo " checked"; } ?> value="Business Sites" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Business Sites
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Site LSD".',') !== FALSE) { echo " checked"; } ?> value="Site LSD" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Site LSD
            <input type="checkbox" <?php if (strpos($contacts_config, ','."Site Number".',') !== FALSE) { echo " checked"; } ?> value="Site Number" style="height: 20px; width:20px;" name="contacts[]">&nbsp;&nbsp;Site #


			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field"	value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>

    	</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Vehicle Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
			   Vehicle Description<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_2" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."License Plate #".',') !== FALSE) { echo " checked"; } ?> value="License Plate #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;License Plate #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload License Plate".',') !== FALSE) { echo " checked"; } ?> value="Upload License Plate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload License Plate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."CARFAX".',') !== FALSE) { echo " checked"; } ?> value="CARFAX" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;CARFAX

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field"	value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Location Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
				Location<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_3" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Address".',') !== FALSE) { echo " checked"; } ?> value="Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Mailing Address".',') !== FALSE) { echo " checked"; } ?> value="Mailing Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Mailing Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Business Address".',') !== FALSE) { echo " checked"; } ?> value="Business Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Business Address

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Ship To Address".',') !== FALSE) { echo " checked"; } ?> value="Ship To Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Ship To Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Postal Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Postal Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Zip/Postal Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."City".',') !== FALSE) { echo " checked"; } ?> value="City" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Province".',') !== FALSE) { echo " checked"; } ?> value="Province" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Province
			<input type="checkbox" <?php if (strpos($contacts_config, ','."State".',') !== FALSE) { echo " checked"; } ?> value="State" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Country".',') !== FALSE) { echo " checked"; } ?> value="Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Country

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Ship Country".',') !== FALSE) { echo " checked"; } ?> value="Ship Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Ship Country
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Ship City".',') !== FALSE) { echo " checked"; } ?> value="Ship City" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Ship City
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Ship State".',') !== FALSE) { echo " checked"; } ?> value="Ship State" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Ship State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Ship Zip".',') !== FALSE) { echo " checked"; } ?> value="Ship Zip" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Ship Zip

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Google Maps Address".',') !== FALSE) { echo " checked"; } ?> value="Google Maps Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Google Maps Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."City Part".',') !== FALSE) { echo " checked"; } ?> value="City Part" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City Part

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Payment Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_4" >
				Payment Description<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_4" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Account Number".',') !== FALSE) { echo " checked"; } ?> value="Account Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Account Number
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment Type".',') !== FALSE) { echo " checked"; } ?> value="Payment Type" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment Type
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment Name".',') !== FALSE) { echo " checked"; } ?> value="Payment Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment Address".',') !== FALSE) { echo " checked"; } ?> value="Payment Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment City".',') !== FALSE) { echo " checked"; } ?> value="Payment City" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment City
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment State".',') !== FALSE) { echo " checked"; } ?> value="Payment State" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment Postal Code".',') !== FALSE) { echo " checked"; } ?> value="Payment Postal Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment Postal Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Payment Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment Zip/Postal Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."GST #".',') !== FALSE) { echo " checked"; } ?> value="GST #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;GST #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."PST #".',') !== FALSE) { echo " checked"; } ?> value="PST #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;PST #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Vendor GST #".',') !== FALSE) { echo " checked"; } ?> value="Vendor GST #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Vendor GST #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Payment Information".',') !== FALSE) { echo " checked"; } ?> value="Payment Information" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Payment Information
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Account Number".',') !== FALSE) { echo " checked"; } ?> value="Account Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Account Number
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Total Monthly Rate".',') !== FALSE) { echo " checked"; } ?> value="Total Monthly Rate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Total Monthly Rate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Total Annual Rate".',') !== FALSE) { echo " checked"; } ?> value="Total Annual Rate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Total Annual Rate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Condo Fees".',') !== FALSE) { echo " checked"; } ?> value="Condo Fees" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Condo Fees
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Deposit".',') !== FALSE) { echo " checked"; } ?> value="Deposit" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Deposit
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Damage Deposit".',') !== FALSE) { echo " checked"; } ?> value="Damage Deposit" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Damage Deposit
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Quote Description
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Pricing Level".',') !== FALSE) { echo " checked"; } ?> value="Pricing Level" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Pricing Level
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cost
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Final Retail Price
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Admin Price
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Wholesale Price
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Commercial Price
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Client Price
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Minimum Billable
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Estimated Hours
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Actual Hours
			<input type="checkbox" <?php if (strpos($contacts_config, ','."MSRP".',') !== FALSE) { echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;MSRP
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Hourly Rate".',') !== FALSE) { echo " checked"; } ?> value="Hourly Rate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Hourly Rate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Monthly Rate".',') !== FALSE) { echo " checked"; } ?> value="Monthly Rate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Monthly Rate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Semi Monthly Rate".',') !== FALSE) { echo " checked"; } ?> value="Semi Monthly Rate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Semi Monthly Rate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Daily Rate".',') !== FALSE) { echo " checked"; } ?> value="Daily Rate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Daily Rate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."HR Rate Work".',') !== FALSE) { echo " checked"; } ?> value="HR Rate Work" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;HR Rate Work
			<input type="checkbox" <?php if (strpos($contacts_config, ','."HR Rate Travel".',') !== FALSE) { echo " checked"; } ?> value="HR Rate Travel" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;HR Rate Travel
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Field Day Cost".',') !== FALSE) { echo " checked"; } ?> value="Field Day Cost" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Field Day Cost
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Field Day Billable".',') !== FALSE) { echo " checked"; } ?> value="Field Day Billable" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Field Day Billable

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Probation Pay Rate".',') !== FALSE) { echo " checked"; } ?> value="Probation Pay Rate" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Probation Pay Rate
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Base Pay".',') !== FALSE) { echo " checked"; } ?> value="Base Pay" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Base Pay
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Performance Pay".',') !== FALSE) { echo " checked"; } ?> value="Performance Pay" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Performance Pay

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Property Descriptions."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
				Property Description<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_5" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Property Information".',') !== FALSE) { echo " checked"; } ?> value="Property Information" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Property Information
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Property Information".',') !== FALSE) { echo " checked"; } ?> value="Upload Property Information" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Property Information
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Unit #".',') !== FALSE) { echo " checked"; } ?> value="Unit #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Unit #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Condo Fees".',') !== FALSE) { echo " checked"; } ?> value="Condo Fees" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Condo Fees
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Base Rent".',') !== FALSE) { echo " checked"; } ?> value="Base Rent" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Base Rent
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Base Rent/Sq. Ft.".',') !== FALSE) { echo " checked"; } ?> value="Base Rent/Sq. Ft." style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Base Rent/Sq. Ft.
			<input type="checkbox" <?php if (strpos($contacts_config, ','."CAC".',') !== FALSE) { echo " checked"; } ?> value="CAC" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;CAC
			<input type="checkbox" <?php if (strpos($contacts_config, ','."CAC/Sq. Ft.".',') !== FALSE) { echo " checked"; } ?> value="CAC/Sq. Ft." style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;CAC/Sq. Ft.
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Property Tax".',') !== FALSE) { echo " checked"; } ?> value="Property Tax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Property Tax
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Property Tax/Sq. Ft.".',') !== FALSE) { echo " checked"; } ?> value="Property Tax/Sq. Ft." style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Property Tax/Sq. Ft.
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Inspection".',') !== FALSE) { echo " checked"; } ?> value="Upload Inspection" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Inspection
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bay #".',') !== FALSE) { echo " checked"; } ?> value="Bay #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bay #

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Contract/Form Information."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_6" >
				Contract/Form Info<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_6" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Letter of Intent".',') !== FALSE) { echo " checked"; } ?> value="Upload Letter of Intent" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Letter of Intent
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Vendor Documents".',') !== FALSE) { echo " checked"; } ?> value="Upload Vendor Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Vendor Documents
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Marketing Material".',') !== FALSE) { echo " checked"; } ?> value="Upload Marketing Material" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Marketing Material
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Purchase Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Purchase Contract" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Purchase Contract
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Support Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Support Contract" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Support Contract
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Support Terms".',') !== FALSE) { echo " checked"; } ?> value="Upload Support Terms" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Support Terms
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Rental Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Rental Contract" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Rental Contract
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Management Contract".',') !== FALSE) { echo " checked"; } ?> value="Upload Management Contract" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Management Contract
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Articles of Incorporation".',') !== FALSE) { echo " checked"; } ?> value="Upload Articles of Incorporation" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Articles of Incorporation
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Option to Renew".',') !== FALSE) { echo " checked"; } ?> value="Option to Renew" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Option to Renew

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Dates."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
				Dates<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_7" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Start Date".',') !== FALSE) { echo " checked"; } ?> value="Start Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Start Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Expiry Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Expiry Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Renewal Date".',') !== FALSE) { echo " checked"; } ?> value="Renewal Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Renewal Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Lease Term Date".',') !== FALSE) { echo " checked"; } ?> value="Lease Term Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Lease Term Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Lease Term - # of years".',') !== FALSE) { echo " checked"; } ?> value="Lease Term - # of years" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Lease Term - # of years
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Date Contract Signed".',') !== FALSE) { echo " checked"; } ?> value="Date Contract Signed" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Date Contract Signed
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Option to Renew Date".',') !== FALSE) { echo " checked"; } ?> value="Option to Renew Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Option to Renew Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Rate Increase Date".',') !== FALSE) { echo " checked"; } ?> value="Rate Increase Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Rate Increase Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Insurance Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Insurance Expiry Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Insurance Expiry Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Account Expiry Date".',') !== FALSE) { echo " checked"; } ?> value="Account Expiry Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Account Expiry Date

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Hire Date".',') !== FALSE) { echo " checked"; } ?> value="Hire Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Hire Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Probation End Date".',') !== FALSE) { echo " checked"; } ?> value="Probation End Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Probation End Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Probation Expiry Reminder Date".',') !== FALSE) { echo " checked"; } ?> value="Probation Expiry Reminder Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Probation Expiry Reminder Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Birth Date".',') !== FALSE) { echo " checked"; } ?> value="Birth Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Birth Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Company Benefit Start Date".',') !== FALSE) { echo " checked"; } ?> value="Company Benefit Start Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Company Benefit Start Date

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Insurance."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
				Insurance<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_8" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Commercial Insurance".',') !== FALSE) { echo " checked"; } ?> value="Upload Commercial Insurance" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Commercial Insurance
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Commercial Insurer".',') !== FALSE) { echo " checked"; } ?> value="Commercial Insurer" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Commercial Insurer
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload Residential Insurance".',') !== FALSE) { echo " checked"; } ?> value="Upload Residential Insurance" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload Residential Insurance
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Residential Insurer".',') !== FALSE) { echo " checked"; } ?> value="Residential Insurer" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Residential Insurer
			<input type="checkbox" <?php if (strpos($contacts_config, ','."WCB #".',') !== FALSE) { echo " checked"; } ?> value="WCB #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;WCB #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Upload WCB".',') !== FALSE) { echo " checked"; } ?> value="Upload WCB" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Upload WCB

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Comments."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
				Comments<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_9" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."General Comments".',') !== FALSE) { echo " checked"; } ?> value="General Comments" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;General Comments
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Comments
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Notes

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Status."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_15" >
				Status<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_15" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Status

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Social Media Links."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sm" >
				Social Media Links<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_sm" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."LinkedIn".',') !== FALSE) { echo " checked"; } ?> value="LinkedIn" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;LinkedIn
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Facebook".',') !== FALSE) { echo " checked"; } ?> value="Facebook" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Facebook
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Twitter".',') !== FALSE) { echo " checked"; } ?> value="Twitter" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Twitter

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Login Information."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_16" >
				Login Information<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_16" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."User Name".',') !== FALSE) { echo " checked"; } ?> value="User Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Username
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Password".',') !== FALSE) { echo " checked"; } ?> value="Password" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Password

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Client Information."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_17" >
				Client Information<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_17" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client First Name".',') !== FALSE) { echo " checked"; } ?> value="Client First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Last Name".',') !== FALSE) { echo " checked"; } ?> value="Client Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Work Phone".',') !== FALSE) { echo " checked"; } ?> value="Client Work Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Work Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Client Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Client Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Fax".',') !== FALSE) { echo " checked"; } ?> value="Client Fax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Fax #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Email Address".',') !== FALSE) { echo " checked"; } ?> value="Client Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Date of Birth".',') !== FALSE) { echo " checked"; } ?> value="Client Date of Birth" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Date of Birth
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Height".',') !== FALSE) { echo " checked"; } ?> value="Client Height" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Height
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Weight".',') !== FALSE) { echo " checked"; } ?> value="Client Weight" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Weight
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client SIN".',') !== FALSE) { echo " checked"; } ?> value="Client SIN" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;SIN #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Client ID".',') !== FALSE) { echo " checked"; } ?> value="Client Client ID" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Client ID #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Client Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Client Address."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_18" >
			   Client Address<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_18" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Address".',') !== FALSE) { echo " checked"; } ?> value="Client Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Client Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Postal/Zip Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client City".',') !== FALSE) { echo " checked"; } ?> value="Client City" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City/Town
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Province".',') !== FALSE) { echo " checked"; } ?> value="Client Province" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Province/State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Country".',') !== FALSE) { echo " checked"; } ?> value="Client Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Country
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Program Address".',') !== FALSE) { echo " checked"; } ?> value="Client Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home/Program Address

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Classification."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_19" >
			   Classification<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_19" class="panel-collapse collapse">
		<div class="panel-body">
		   <input type="checkbox" <?php if (strpos($contacts_config, ','."Classification Group Home 1".',') !== FALSE) { echo " checked"; } ?> value="Classification Group Home 1" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Group Home 1
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Classification Group Home 2".',') !== FALSE) { echo " checked"; } ?> value="Classification Group Home 2" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Group Home 2
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Classification Day Program 1".',') !== FALSE) { echo " checked"; } ?> value="Classification Day Program 1" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Day Program 1
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Classification Day Program 2".',') !== FALSE) { echo " checked"; } ?> value="Classification Day Program 2" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Day Program 2

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Transportation."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_20" >
			   Transportation<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_20" class="panel-collapse collapse">
		<div class="panel-body">
		   <input type="checkbox" <?php if (strpos($contacts_config, ','."Transportation Mode of Transportation".',') !== FALSE) { echo " checked"; } ?> value="Transportation Mode of Transportation" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Mode of Transportation
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transportation Transit Access".',') !== FALSE) { echo " checked"; } ?> value="Transportation Transit Access" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Transit Access
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transportation Access Password".',') !== FALSE) { echo " checked"; } ?> value="Transportation Access Password" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Access Password
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transportation Drivers License".',') !== FALSE) { echo " checked"; } ?> value="Transportation Drivers License" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Driver's Licence
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Drivers License Class".',') !== FALSE) { echo " checked"; } ?> value="Drivers License Class" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Driver's Licence Class
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Drive Manual Transmission".',') !== FALSE) { echo " checked"; } ?> value="Drive Manual Transmission" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Drive Manual Transmission
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transportation Drivers Glasses".',') !== FALSE) { echo " checked"; } ?> value="Transportation Drivers Glasses" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Driver Requires Glasses/Contacts
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transportation Upload License".',') !== FALSE) { echo " checked"; } ?> value="Transportation Upload License" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Transportation Upload Licence
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transportation Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Transportation Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Financial."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_financial" >
			   Financial<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_financial" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Void Cheque".',') !== FALSE) { echo " checked"; } ?> value="Void Cheque" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Void Cheque
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bank Name".',') !== FALSE) { echo " checked"; } ?> value="Bank Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bank Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bank Institution Number".',') !== FALSE) { echo " checked"; } ?> value="Bank Institution Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bank Institution Number
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bank Transit Number".',') !== FALSE) { echo " checked"; } ?> value="Bank Transit Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bank Transit Number
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bank Account Number".',') !== FALSE) { echo " checked"; } ?> value="Bank Account Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bank Account Number

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Emergency Fields."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_emergency" >
			   Emergency Information<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_emergency" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Primary Emergency Contact First Name".',') !== FALSE) { echo " checked"; } ?> value="Primary Emergency Contact First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Primary Emergency Contact First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Primary Emergency Contact Last Name".',') !== FALSE) { echo " checked"; } ?> value="Primary Emergency Contact Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Primary Emergency Contact Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Primary Emergency Contact Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Primary Emergency Contact Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Primary Emergency Contact Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Primary Emergency Contact Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Primary Emergency Contact Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Primary Emergency Contact Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Primary Emergency Contact Email".',') !== FALSE) { echo " checked"; } ?> value="Primary Emergency Contact Email" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Primary Emergency Contact Email
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Primary Emergency Contact Relationship".',') !== FALSE) { echo " checked"; } ?> value="Primary Emergency Contact Relationship" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Primary Emergency Contact Relationship
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Secondary Emergency Contact First Name".',') !== FALSE) { echo " checked"; } ?> value="Secondary Emergency Contact First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Secondary Emergency Contact First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Secondary Emergency Contact Last Name".',') !== FALSE) { echo " checked"; } ?> value="Secondary Emergency Contact Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Secondary Emergency Contact Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Secondary Emergency Contact Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Secondary Emergency Contact Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Secondary Emergency Contact Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Secondary Emergency Contact Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Secondary Emergency Contact Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Secondary Emergency Contact Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Secondary Emergency Contact Email".',') !== FALSE) { echo " checked"; } ?> value="Secondary Emergency Contact Email" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Secondary Emergency Contact Email
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Secondary Emergency Contact Relationship".',') !== FALSE) { echo " checked"; } ?> value="Secondary Emergency Contact Relationship" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Secondary Emergency Contact Relationship

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Health Care & Insurance."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_21" >
			   Health Care & Insurance<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_21" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Health Care Number".',') !== FALSE) { echo " checked"; } ?> value="Health Care Number" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Health Care Number
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Health Concerns".',') !== FALSE) { echo " checked"; } ?> value="Health Concerns" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Health Concerns
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Emergency Procedure".',') !== FALSE) { echo " checked"; } ?> value="Emergency Procedure" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Emergency Procedure
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications".',') !== FALSE) { echo " checked"; } ?> value="Medications" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Allergies".',') !== FALSE) { echo " checked"; } ?> value="Allergies" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Allergies
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Allergy Procedure".',') !== FALSE) { echo " checked"; } ?> value="Allergy Procedure" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Allergy Procedure
		   <input type="checkbox" <?php if (strpos($contacts_config, ','."Insurance Alberta Health Care".',') !== FALSE) { echo " checked"; } ?> value="Insurance Alberta Health Care" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Alberta Health Care
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Insurance AISH Entrance Date".',') !== FALSE) { echo " checked"; } ?> value="Insurance AISH Entrance Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;AISH Entrance Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Insurance AISH #".',') !== FALSE) { echo " checked"; } ?> value="Insurance AISH #" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;AISH #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Insurance Client ID".',') !== FALSE) { echo " checked"; } ?> value="Insurance Client ID" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Client ID
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Insurance Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Insurance Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Guardian."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_22" >
			   Guardian<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_22" class="panel-collapse collapse">
		<div class="panel-body">
		   <input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Family Guardian".',') !== FALSE) { echo " checked"; } ?> value="Guardians Family Guardian" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Family Guardian
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Family Appointed Guardian".',') !== FALSE) { echo " checked"; } ?> value="Guardians Family Appointed Guardian" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Family Appointed Guardian
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Public Guardian".',') !== FALSE) { echo " checked"; } ?> value="Guardians Public Guardian" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Public Guardian
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Court Appointed Guardian".',') !== FALSE) { echo " checked"; } ?> value="Guardians Court Appointed Guardian" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Court Appointed Guardian
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians First Name".',') !== FALSE) { echo " checked"; } ?> value="Guardians First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Last Name".',') !== FALSE) { echo " checked"; } ?> value="Guardians Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Work Phone".',') !== FALSE) { echo " checked"; } ?> value="Guardians Work Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Work Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Guardians Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Guardians Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Fax".',') !== FALSE) { echo " checked"; } ?> value="Guardians Fax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Fax #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Email Address".',') !== FALSE) { echo " checked"; } ?> value="Guardians Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Address".',') !== FALSE) { echo " checked"; } ?> value="Guardians Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Guardians Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Postal/Zip Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Town".',') !== FALSE) { echo " checked"; } ?> value="Guardians Town" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City/Town
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Province".',') !== FALSE) { echo " checked"; } ?> value="Guardians Province" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Province/State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Country".',') !== FALSE) { echo " checked"; } ?> value="Guardians Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Country
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Guardians Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Guardians Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Trustee."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_23" >
			   Trustee<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_23" class="panel-collapse collapse">
		<div class="panel-body">
		   <input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Family Trustee".',') !== FALSE) { echo " checked"; } ?> value="Trustee Family Trustee" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Family Trustee
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Family Appointed Trustee".',') !== FALSE) { echo " checked"; } ?> value="Trustee Family Appointed Trustee" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Family Appointed Trustee
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Public Trustee".',') !== FALSE) { echo " checked"; } ?> value="Trustee Public Trustee" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Public Trustee
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Court Appointed Trustee".',') !== FALSE) { echo " checked"; } ?> value="Trustee Court Appointed Trustee" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Court Appointed Trustee
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee First Name".',') !== FALSE) { echo " checked"; } ?> value="Trustee First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Last Name".',') !== FALSE) { echo " checked"; } ?> value="Trustee Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Work Phone".',') !== FALSE) { echo " checked"; } ?> value="Trustee Work Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Work Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Trustee Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Trustee Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Fax".',') !== FALSE) { echo " checked"; } ?> value="Trustee Fax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Fax #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Email Address".',') !== FALSE) { echo " checked"; } ?> value="Trustee Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Address".',') !== FALSE) { echo " checked"; } ?> value="Trustee Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Trustee Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Postal/Zip Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Town".',') !== FALSE) { echo " checked"; } ?> value="Trustee Town" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City/Town
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Province".',') !== FALSE) { echo " checked"; } ?> value="Trustee Province" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Province/State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Country".',') !== FALSE) { echo " checked"; } ?> value="Trustee Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Country
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Trustee Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Trustee Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Family Doctor."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_24" >
			   Family Doctor<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_24" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor First Name".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Last Name".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Work Phone".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Work Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Work Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Fax".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Fax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Fax #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Email Address".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Address".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Postal/Zip Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Town".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Town" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City/Town
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Province".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Province" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Province/State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Country".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Country
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Family Doctor Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Family Doctor Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Dentist."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_25" >
			   Dentist<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_25" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist First Name".',') !== FALSE) { echo " checked"; } ?> value="Dentist First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Last Name".',') !== FALSE) { echo " checked"; } ?> value="Dentist Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Work Phone".',') !== FALSE) { echo " checked"; } ?> value="Dentist Work Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Work Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Dentist Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Dentist Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Fax".',') !== FALSE) { echo " checked"; } ?> value="Dentist Fax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Fax #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Email Address".',') !== FALSE) { echo " checked"; } ?> value="Dentist Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Address".',') !== FALSE) { echo " checked"; } ?> value="Dentist Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Dentist Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Postal/Zip Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Town".',') !== FALSE) { echo " checked"; } ?> value="Dentist Town" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City/Town
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Province".',') !== FALSE) { echo " checked"; } ?> value="Dentist Province" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Province/State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Country".',') !== FALSE) { echo " checked"; } ?> value="Dentist Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Country
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Dentist Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Dentist Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Specialist."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_26" >
			   Specialist<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_26" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists First Name".',') !== FALSE) { echo " checked"; } ?> value="Specialists First Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Last Name".',') !== FALSE) { echo " checked"; } ?> value="Specialists Last Name" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Last Name
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Work Phone".',') !== FALSE) { echo " checked"; } ?> value="Specialists Work Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Work Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Home Phone".',') !== FALSE) { echo " checked"; } ?> value="Specialists Home Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Home Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Cell Phone".',') !== FALSE) { echo " checked"; } ?> value="Specialists Cell Phone" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Cell Phone
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Fax".',') !== FALSE) { echo " checked"; } ?> value="Specialists Fax" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Fax #
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Email Address".',') !== FALSE) { echo " checked"; } ?> value="Specialists Email Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Email Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Address".',') !== FALSE) { echo " checked"; } ?> value="Specialists Address" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Address
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Zip Code".',') !== FALSE) { echo " checked"; } ?> value="Specialists Zip Code" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Postal/Zip Code
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Town".',') !== FALSE) { echo " checked"; } ?> value="Specialists Town" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;City/Town
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Province".',') !== FALSE) { echo " checked"; } ?> value="Specialists Province" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Province/State
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Country".',') !== FALSE) { echo " checked"; } ?> value="Specialists Country" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Country
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Specialists Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Specialists Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Diagnosis."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_27" >
			   Diagnosis<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_27" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medical Details Diagnosis".',') !== FALSE) { echo " checked"; } ?> value="Medical Details Diagnosis" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Diagnosis
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Diagnosis Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Diagnosis Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Allergies."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_28" >
			   Allergies<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_28" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medical Details Allergies".',') !== FALSE) { echo " checked"; } ?> value="Medical Details Allergies" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Allergies
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Allergies Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Allergies Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Equipment."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_29" >
			   Equipment<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_29" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medical Details Equipment".',') !== FALSE) { echo " checked"; } ?> value="Medical Details Equipment" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Equipment
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Equipment Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Equipment Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the First Aid/CPR."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_30" >
			   First Aid/CPR<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_30" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medical Details First Aid/CPR".',') !== FALSE) { echo " checked"; } ?> value="Medical Details First Aid/CPR" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;First Aid/CPR
			<input type="checkbox" <?php if (strpos($contacts_config, ','."First Aid/CPR Support Documents".',') !== FALSE) { echo " checked"; } ?> value="First Aid/CPR Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Medical Details Support Documents."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_31" >
			   Medical Details Support Documents<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_31" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medical Details Support Documents".',') !== FALSE) { echo " checked"; } ?> value="Medical Details Support Documents" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Support Documents

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Medications."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_32" >
			   Medications<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_32" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Client Profile".',') !== FALSE) { echo " checked"; } ?> value="Medications Client Profile" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications Client Profile
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Daily Log Notes".',') !== FALSE) { echo " checked"; } ?> value="Medications Daily Log Notes" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications Daily Log Notes
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Medications Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Start Time".',') !== FALSE) { echo " checked"; } ?> value="Medications Start Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications Start Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications End Time".',') !== FALSE) { echo " checked"; } ?> value="Medications End Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications End Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Completed By".',') !== FALSE) { echo " checked"; } ?> value="Medications Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Medications Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications Signature Box
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Management Comments".',') !== FALSE) { echo " checked"; } ?> value="Medications Management Comments" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Comments
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Management Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Medications Management Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Management Completed By".',') !== FALSE) { echo " checked"; } ?> value="Medications Management Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Management Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Medications Management Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Signature Box

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Protocols Details."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_33" >
			   Protocols Details<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_33" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Seizure Protocol Details".',') !== FALSE) { echo " checked"; } ?> value="Seizure Protocol Details" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Seizure Protocol Details
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Seizure Protocol Upload".',') !== FALSE) { echo " checked"; } ?> value="Seizure Protocol Upload" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Seizure Protocol Upload

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Slip Fall Protocol Details".',') !== FALSE) { echo " checked"; } ?> value="Slip Fall Protocol Details" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Slip & Fall Protocol Details
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Slip Fall Protocol Upload".',') !== FALSE) { echo " checked"; } ?> value="Slip Fall Protocol Upload" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Slip & Fall Protocol Upload

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transfer Protocol Details".',') !== FALSE) { echo " checked"; } ?> value="Transfer Protocol Details" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Transfer Protocol Details
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Transfer Protocol Upload".',') !== FALSE) { echo " checked"; } ?> value="Transfer Protocol Upload" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Transfer Protocol Upload

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Toileting Protocol Details".',') !== FALSE) { echo " checked"; } ?> value="Toileting Protocol Details" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Toileting Protocol Details
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Toileting Protocol Upload".',') !== FALSE) { echo " checked"; } ?> value="Toileting Protocol Upload" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Toileting Protocol Upload

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bathing Protocol Details".',') !== FALSE) { echo " checked"; } ?> value="Bathing Protocol Details" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bathing Protocol Details
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Bathing Protocol Upload".',') !== FALSE) { echo " checked"; } ?> value="Bathing Protocol Upload" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bathing Protocol Upload

			<input type="checkbox" <?php if (strpos($contacts_config, ','."G-Tube Protocol Details".',') !== FALSE) { echo " checked"; } ?> value="G-Tube Protocol Details" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;G-Tube Protocol Details
			<input type="checkbox" <?php if (strpos($contacts_config, ','."G-Tube Protocol Upload".',') !== FALSE) { echo " checked"; } ?> value="G-Tube Protocol Upload" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Bathing Protocol Upload

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Oxygen Protocol Details".',') !== FALSE) { echo " checked"; } ?> value="Oxygen Protocol Details" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Oxygen Protocol Details
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Oxygen Protocol Upload".',') !== FALSE) { echo " checked"; } ?> value="Oxygen Protocol Upload" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Oxygen Protocol Upload

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Daily Log Notes Protocols Details."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_34" >
			   Daily Log Notes Protocols Details<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_34" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Daily Log Notes".',') !== FALSE) { echo " checked"; } ?> value="Protocols Daily Log Notes" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Protocols Daily Log Notes
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Protocols Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Protocols Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Start Time".',') !== FALSE) { echo " checked"; } ?> value="Protocols Start Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Protocols Start Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols End Time".',') !== FALSE) { echo " checked"; } ?> value="Protocols End Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Protocols End Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Completed By".',') !== FALSE) { echo " checked"; } ?> value="Protocols Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Protocols Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Protocols Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Protocols Signature Box
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Management Comments".',') !== FALSE) { echo " checked"; } ?> value="Protocols Management Comments" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Comments
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Management Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Protocols Management Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Management Completed By".',') !== FALSE) { echo " checked"; } ?> value="Protocols Management Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Protocols Management Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Protocols Management Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Signature Box

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Daily Log Notes Routines Details."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_35" >
			   Daily Log Notes Routines Details<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_35" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Daily Log Notes".',') !== FALSE) { echo " checked"; } ?> value="Routines Daily Log Notes" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Routines Daily Log Notes
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Routines Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Routines Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Start Time".',') !== FALSE) { echo " checked"; } ?> value="Routines Start Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Routines Start Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines End Time".',') !== FALSE) { echo " checked"; } ?> value="Routines End Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Routines End Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Completed By".',') !== FALSE) { echo " checked"; } ?> value="Routines Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Routines Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Routines Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Routines Signature Box
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Management Comments".',') !== FALSE) { echo " checked"; } ?> value="Routines Management Comments" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Comments
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Management Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Routines Management Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Management Completed By".',') !== FALSE) { echo " checked"; } ?> value="Routines Management Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Routines Management Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Routines Management Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Signature Box

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Daily Log Notes Communication Details."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_36" >
			   Daily Log Notes Communication Details<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_36" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Daily Log Notes".',') !== FALSE) { echo " checked"; } ?> value="Communication Daily Log Notes" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Communication Daily Log Notes
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Communication Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Communication Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Start Time".',') !== FALSE) { echo " checked"; } ?> value="Communication Start Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Communication Start Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication End Time".',') !== FALSE) { echo " checked"; } ?> value="Communication End Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Communication End Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Completed By".',') !== FALSE) { echo " checked"; } ?> value="Communication Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Communication Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Communication Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Communication Signature Box
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Management Comments".',') !== FALSE) { echo " checked"; } ?> value="Communication Management Comments" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Comments
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Management Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Communication Management Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Management Completed By".',') !== FALSE) { echo " checked"; } ?> value="Communication Management Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Communication Management Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Communication Management Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Signature Box

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose the Daily Log Notes Activities Details."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_37" >
			   Daily Log Notes Activities Details<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_37" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Daily Log Notes".',') !== FALSE) { echo " checked"; } ?> value="Activities Daily Log Notes" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Activities Daily Log Notes
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Activities Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Activities Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Start Time".',') !== FALSE) { echo " checked"; } ?> value="Activities Start Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Activities Start Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities End Time".',') !== FALSE) { echo " checked"; } ?> value="Activities End Time" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Activities End Time
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Completed By".',') !== FALSE) { echo " checked"; } ?> value="Activities Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Activities Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Activities Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Activities Signature Box
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Management Comments".',') !== FALSE) { echo " checked"; } ?> value="Activities Management Comments" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Comments
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Management Completed Date".',') !== FALSE) { echo " checked"; } ?> value="Activities Management Completed Date" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed Date
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Management Completed By".',') !== FALSE) { echo " checked"; } ?> value="Activities Management Completed By" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Completed By
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Activities Management Signature Box".',') !== FALSE) { echo " checked"; } ?> value="Activities Management Signature Box" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Management Signature Box

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose Injury."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_38" >
			   Injury<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_38" class="panel-collapse collapse">
		<div class="panel-body">
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Injury".',') !== FALSE) { echo " checked"; } ?> value="Injury" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Injury

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose Injury."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_social" >
			   Social Story Information<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_social" class="panel-collapse collapse">
		<div class="panel-body">
			<p><b><em>The following options will display the information from the Social Stories tile, and allow the user to add details.</em></b></p>
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Key Methodologies Social Story".',') !== FALSE) { echo " checked"; } ?> value="Client Key Methodologies Social Story" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Key Methodologies
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Protocols Social Story".',') !== FALSE) { echo " checked"; } ?> value="Client Protocols Social Story" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Protocols
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Routines Social Story".',') !== FALSE) { echo " checked"; } ?> value="Client Routines Social Story" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Routines
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Communication Social Story".',') !== FALSE) { echo " checked"; } ?> value="Client Communication Social Story" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Communication
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Activities Social Story".',') !== FALSE) { echo " checked"; } ?> value="Client Activities Social Story" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Activities

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose Injury."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tiles" >
			   Tile Data<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_tiles" class="panel-collapse collapse">
		<div class="panel-body">
			<p><b><em>The following options will display the information from a Tile, and allow the user to add details.</em></b></p>
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Support Plan".',') !== FALSE) { echo " checked"; } ?> value="Client Support Plan" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Individual Service Plan (ISP)
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Medications Client Profile".',') !== FALSE) { echo " checked"; } ?> value="Medications Client Profile" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medications
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Medical Charts".',') !== FALSE) { echo " checked"; } ?> value="Client Medical Charts" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Medical Charts
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Client Daily Log Notes".',') !== FALSE) { echo " checked"; } ?> value="Client Daily Log Notes" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Daily Log Notes

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to choose Account Details."><img src=" <?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_39" >
			   Account Details<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_39" class="panel-collapse collapse">
		<div class="panel-body">

			<input type="checkbox" <?php if (strpos($contacts_config, ','."Accounts Receivable/Credit on Account".',') !== FALSE) { echo " checked"; } ?> value="Accounts Receivable/Credit on Account" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Accounts Receivable/Credit on Account
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Patient Accounts Receivable".',') !== FALSE) { echo " checked"; } ?> value="Patient Accounts Receivable" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Patient Accounts Receivable
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Insurer Accounts Receivable for Patient".',') !== FALSE) { echo " checked"; } ?> value="Insurer Accounts Receivable for Patient" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Insurer Accounts Receivable for Patient
			<input type="checkbox" <?php if (strpos($contacts_config, ','."All Patient Invoices".',') !== FALSE) { echo " checked"; } ?> value="All Patient Invoices" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;All Patient Invoices
			<input type="checkbox" <?php if (strpos($contacts_config, ','."All Insurer Invoices for Patient".',') !== FALSE) { echo " checked"; } ?> value="All Insurer Invoices for Patient" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;All Insurer Invoices for Patient
			<input type="checkbox" <?php if (strpos($contacts_config, ','."Account Statement".',') !== FALSE) { echo " checked"; } ?> value="Account Statement" style="height: 20px; width: 20px;" name="contacts[]">&nbsp;&nbsp;Account Statement

			<br><br>
			<div class="form-group">
				<div class="col-sm-6 clearfix">
					<a href="contacts.php?category=Business&filter=Top" class="btn config-btn btn-lg">Back</a>
					<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button	type="submit" name="inv_field" value="inv_field" class="btn config-btn btn-lg pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
