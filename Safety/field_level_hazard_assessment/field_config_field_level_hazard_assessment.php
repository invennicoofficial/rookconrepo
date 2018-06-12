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
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields118,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields118">Date
&nbsp;&nbsp;
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields119,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields119">Job
&nbsp;&nbsp;
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields120,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields120">Contact
&nbsp;&nbsp;
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields121,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields121">Job Location
<br><br>
PERMITS/PLANS
<ul style="list-style-type: none;">
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">Hot Work/Cold Work</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Confined Space</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Demolition</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields108,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields108">Ground Disturbance</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Excavation</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Lockout</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Critical Lift Plan</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Fall Protection Plan</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields161,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields161">Road Closure Permit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields162,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields162">Locates Expiration</li>
</ul>

PERMIT IDENTIFIED HAZARDS
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Hazards Detailed on Safe Work Permit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Hazards on Critical Lift Permit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Hazards on Electrical Permit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Hazards Identified for Confined Space Entry</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Hazards on Confined Space Entry Permit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Hazards on Hot/Cold Work Permit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Hazards on Underground/ Excavation, Permit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Hazards on Line Opening Permit</li>
</ul>

EMERGENCY EQUIPMENT
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Fire Extinguisher</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Eyewash/Shower</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields109,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields109">All Conditions Met</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Extraction Equipment</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Permit Displayed</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields157,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields157">First Aid Kit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields158,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields158">Spill Kit</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields159,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields159">Road Flares</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields160,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields160">Location of Emergency Equipment</li>
    <li>Alarm#</li>
</ul>

OVERHEAD OR WORKING AT HEIGHT HAZARDS
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">Harness Required/Appropriate Tie-off identified</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Others Working Overhead/Below</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Hoisting or moving loads overhead</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Falls from Height</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24">Hoisting or moving Loads Overhead/Around Task</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields110,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields110">Use of Scaffolds</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25">Tasks Require You to Work Above Your Task</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26">Objects / Debris Falling from Above</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27">Overhead Power Line</li>
</ul>

EQUIPMENT HAZARDS
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28">Operating Power Equipment</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29">Operating Motor Vehicle / Heavy Equipment</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30">Contact with/contact by</li>
    <li>Working with:</li>
    <li>
        <ul style="list-style-type: none;">
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31">Saws</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32">Cutting Torch Equipment</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33">Hand Tools</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34">Grinders</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35">Welding Machines</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36">Cranes</li>
        </ul>
    </li>
</ul>

WORK ENVIRONMENT HAZARDS
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37">Weather Conditions</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38">Slips or Trips Possible</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39">Waste Material Generated Performing Task</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40">Limited Access / Egress</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41">Foreign Bodies in Eyes</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42">Exposure to Energized Electrical Systems</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields43,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields43">Lighing Levels Too High/Too Low</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields44,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields44">Position of Fingers / Hands - Pinch Points</li>

    <li>Exposure to:</li>
    <li>
        <ul style="list-style-type: none;">
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields45,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields45">Chemicals</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields46,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields46"> Dust/Particulates</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47">Extreme Heat/Cold</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48">Reactive Chemicals</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields49,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields49">Sharp Objects / Edges</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields50,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields50">Noise</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields51,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields51">Odors</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields52,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields52">Steam</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields53,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields53">Fogging of Monogoggles / Bye Protection</li>
            <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields54,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields54">Flammable gases / Atmospheric hazards</li>
        </ul>
    </li>
</ul>

PERSONAL LIMITATIONS/HAZARDS
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields55,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields55">Procedure Not Available for Task</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields56,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields56">Confusing Instructions</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields57,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields57">No Training in Procedure / Task</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields58,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields58">No Training in Tools to be Used</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields59,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields59">First Time Performing This Task</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields60,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields60">Mental Limitations / Distractions / Loss of Focus</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields61,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields61">Not Physically Able to Perform Task</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields62,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields62">Complacency</li>
</ul>

WELDING
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields63,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields63">Shields</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields64,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields64">Fire Blankets</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields65,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields65">Fire Extinguisher</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields66,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields66">Cylinder Secured / Secure Connections</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields67,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields67">Cylinder Caps On</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields68,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields68">Flashback Arrestor</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields69,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields69">Combustibles Moved</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields70,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields70">Sparks Contained</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields71,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields71">Ground within 18 inch</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields72,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields72">Fire Watch / Spark Watch</li>
</ul>

PHYSICAL HAZARDS
<ul style="list-style-type: none;">
    Manual Lifting
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields73,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields73">Load Too Heavy / Awkward to Lift</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields74,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields74">Over Reaching</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields75,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields75">Prolonged / Extreme Bending</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields76,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields76">Repetitive Motions</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields77,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields77">Unstable Position</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields78,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields78">Part(s) of Body in Line of Fire</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields79,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields79">Hands Not in Line of Sight</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields80,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields80">Working in Tight Clearances</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields81,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields81">Physical Limitation - Need Assistance</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields82,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields82">Uncontrolled Release of Energy / Force</li>
    <li><input type="checkbox"   <?php if (strpos(','.$fields.',', ',fields83,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields83">Fall Potential</li>
</ul>

COMMON HAZARDS
<ul style="list-style-type: none;">
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields122,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields122">Overhead Powerlines</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields123,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields123">Underground Hazards (Gas Lines)</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields124,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields124">Traffic</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields125,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields125">Pedestrians</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields126,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields126">Open Excavation</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields127,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields127">Working Around Extreme Heat</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields128,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields128">Heavy Lifting</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields129,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields129">Working Alone</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields130,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields130">Weather (heat, rain, snow)</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields131,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields131">Noise</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields132,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields132">Working From Heights</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields133,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields133">Dust, Gases, Fumes</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields134,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields134">Spraying Chemicals</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields135,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields135">Faulty Equipment</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields136,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields136">Branches Hitting Face and Eyes</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields137,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields137">Slips, Trips, and Falls</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields164,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields164">Hypothermia/ Frostbite</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields165,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields165">Poor Lighting</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields166,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields166">Ergonomic Strain (shoveling)</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields138,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields138">Other Hazards</li>
</ul>

JOB SCOPE
<ul style="list-style-type: none;">
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields139,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields139">Mowing</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields140,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields140">Line Painting</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields141,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields141">Construction/ Hardscaping</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields142,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields142">Irrigation Start Up/ Breakdown</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields143,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields143">Irrigation Repair</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields144,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields144">Pesticide Spraying</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields145,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields145">Summer Maintenance</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields146,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields146">Spring Clean Up</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields147,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields147">Fall Clean Up</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields148,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields148">Power Washing/ Sanding</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields149,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields149">Indoor</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields150,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields150">Tree Planting/ Removal</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields151,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields151">Pruning</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields152,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields152">Watering</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields153,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields153">Parkade Scrubbing</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields154,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields154">Street Sweeping</li>
    <li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields155,') !== FALSE) { echo " checked"; } ?> name="fields[]" value="fields155">Other Job Scope</li>
</ul>

PERSONAL PROTECTIVE EQUIPMENT
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields84,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields84">Work Gloves</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields85,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields85">Chemical Gloves</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields86,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields86">Kevlar Gloves</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields87,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields87">Rain Gear</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields88,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields88">Thermal Suits</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields89,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields89">Rubber Boots</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields90,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields90">Monogoggles/Faceshield</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields91,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields91">Safety Glasses</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields92,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields92">Respiratory Protection</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields93,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields93">Hearing Protection</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields94,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields94">Safety Harness/Lanyard/Lifeline</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields95,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields95">Head Protection</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields96,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields96">Steel-toed Work Boots</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields97,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields97">Hi-Vis Vest</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields98,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields98">Fire Retardant Wear</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields156,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields156">Cut Proof Gloves/ Clothing</li>
</ul>

WALK AROUND/INSPECTION
<ul style="list-style-type: none;">
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields99,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields99">Leaks</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields100,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields100">Oil</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields101,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields101">Fuel</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields102,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields102">Tires</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields103,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields103">Lights</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields104,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields104">Windows</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields105,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields105">Hoses</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields106,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields106">Alarms</li>
    <li><input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields107,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields107">Bolts</li>
</ul>

<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields111,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields111">Is this worker working alone?
<br>
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields112,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields112">Task(s)
<br>
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields113,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields113">Cliean  Up /  Close  Out-  Job Completion
<br>
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields114,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields114">Designated First Aider
<br>
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields115,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields115">Single Driver
<br>
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields116,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields116">Comments / Notes
<br>
<input type="checkbox"  <?php if (strpos(','.$fields.',', ',fields117,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields117">Workers on Crew
<br>
</div>