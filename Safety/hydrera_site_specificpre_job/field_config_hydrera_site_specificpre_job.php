<div class="form-group">
<label for="file[]" class="col-sm-4 control-label">Upload Logo
<span class="popover-examples list-inline">&nbsp;
<a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
</span>
:</label>
<div class="col-sm-8">
<?php if($pdf_logo != '') {
    echo '<a href="download/'.$pdf_logo.'" target="_blank">View</a>';
    ?>
    <input type="hidden" name="logo_file" value="<?php echo $pdf_logo; ?>" />
    <input name="pdf_logo" type="file" data-filename-placement="inside" class="form-control" />
  <?php } else { ?>
  <input name="pdf_logo" type="file" data-filename-placement="inside" class="form-control" />
  <?php } ?>
</div>
</div>

<!-- Header & Footer -->
<div class="form-group">
    <label for="office_country" class="col-sm-4 control-label">Header Info:<br><em>(Ex: Company Address, Phone, Email etc)</em></label>
    <div class="col-sm-8">
        <textarea name="pdf_header" rows="3" cols="50" class="form-control"><?php echo $pdf_header; ?></textarea>
    </div>
</div>
<div class="form-group">
    <label for="office_country" class="col-sm-4 control-label">Footer Info:<br><em>(e.g. - company name, address, phone, etc.)</em></label>
    <div class="col-sm-8">
        <textarea name="pdf_footer" rows="3" cols="50" class="form-control"><?php echo $pdf_footer; ?></textarea>
    </div>
</div>
<!-- Header & Footer -->


<input type="checkbox" class="selecctall"/> Select All
<br><br>
<div class="field_config">
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">DATE &nbsp;&nbsp;
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Start Site#
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Company &nbsp;&nbsp;
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Job Description
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">LSD &nbsp;&nbsp;
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Contact
<br>
Safety Checklist
    <ul style="list-style-type: none;">
        <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Equipment operation</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Wildlife</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Equipment backing</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Awareness of others</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Overhead work</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Compressed gas cylinder</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">cranes/hoisting</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Driving habit</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Electrical hazards</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Housekeeping/insp.</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Good communication</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Working at heights</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Ignition sources</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">Wind Direction</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Excavation</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Ongoing Hazard Management</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Cuts/Sharps</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24">Manual Lifting</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25">Mechanical lifting</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26">Slip/trips/falls</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27">Working Alone</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28">Overhead lines</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29">Pinch points / crushing</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30">Rigging/ropes/slings/cable</li>
    </ul>

    Safety Equipment / PPE
    <ul style="list-style-type: none;">
        <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31">Tag lines</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32">Shoring</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33">Gloves</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34">Fire extinguishers</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35">Foot protection</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36">Warning signs</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37">Hard hat</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38">Full body clothing(FRC)</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39">Fall arrest protection system</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40">Eye protection</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41">Ground distrubance</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42">SCBA (H2S site)</li>
    </ul>

    Procedures / Checklist
    <ul style="list-style-type: none;">
        <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields43,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields43">Change managemet</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields44,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields44">Confined space</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields45,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields45">Emergency response/Muster Pt.</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields46,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields46">Incident reporting</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47">Towing vehicles</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48">Lockout / tag out</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields49,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields49">H2S</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields50,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields50">Orientations</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields51,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields51">Scaffold inspection</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields52,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields52">Smoking</li>
    </ul>

    <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields53,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields53">Hazards, Controls, Type Of Control Measure, Risk
    <br><br>
    Attendance/Traing
    <ul style="list-style-type: none;">
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields54,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields54">Orient</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields55,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields55">H2S</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields56,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields56">1st Aid</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields57,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields57">TDG</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields58,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields58">Confined Space</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields59,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields59">WHMIS</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields60,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields60">Gr.Dis.</li>
        <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields61,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields61">PST</li>
    </ul>

    <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields62,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields62">Time
    <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields63,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields63">Location / Job #
    <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields64,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields64">Safety Topic
    <input type="checkbox" <?php if (strpos(','.$fields.',', ',fields65,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields65">Concerns
</div>