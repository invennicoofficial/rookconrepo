<body>
  <b>PAGE 1 </b>
    <table border="1" width="100%">
        <tr>
          <td colspan="2">&nbsp;</td>
          <td>Seven Digit Claim # (if available):<input type="text" name="claim_number" style="width:30%;display:inline" class="form-control"></td>
        </tr>
        <tr>
            <td width="10%"><b>Claim Type</b></td>
            <td width="40%">
              <table border="0">
                  <tr>
                    <td width="10%"><b>1</b></td>
                    <td width="30%"><table><tr><td><input class="form-control" type="radio" name="claim_type" value="Time Lost"><b>Time Lost</b></td></tr></table></td>
                    <td width="40%"><table><tr><td><input class="form-control" type="radio" name="claim_type" value="Modified Work"><b>Modified Work</b></td></tr></table></td>
                    <td width="30%"><table><tr><td><input class="form-control" type="radio" name="claim_type" value="Fatality"><b>Fatality</b></td></tr></table></td>
                  </tr>
                </table>
            </td>
            <td width="40%">
              <input type="radio" class="form-control" name="claim_type" value="No Time Lost"><b>No Time Lost (Notice of non-disabling injury/illness)</b>
            </td>
          </tr>
    </table>
    <table border="1" width="100%">
      <tr>
        <td style="font-size:17px;"><b>Worker Details</b></td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
                <td width="15%">Last Name:</td>
                <td width="20%"><input class="form-control" type="text" value="" name="last_name"></td>
                <td width="15%" style="padding-left:25px">First Name:</td>
                <td width="25%"><input class="form-control" type="text" value="" name="first_name"></td>
                <td width="10%" style="padding-left:45px">Initial:</td>
                <td width="5%"><input class="form-control" type="text" value="" name="Initial"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
          <td>
            <table width="100%">
              <tr>
                <td width="10%">Mailing Address: Apt#</td>
                <td width="50%"><input class="form-control" type="text" value="" name="mailing_address"></td>
                <td width="10%" style="padding-left:25px">Social Insurance #:</td>
                <td width="40%">
                  <input style="width:5%;" type="text" value="" name="soc1">
                  <input style="width:5%;" type="text" value="" name="soc2">
                  <input style="width:5%;" type="text" value="" name="soc3">
                  <input style="width:5%;" type="text" value="" name="soc4">
                  <input style="width:5%;" type="text" value="" name="soc5">
                  <input style="width:5%;" type="text" value="" name="soc6">
                  <input style="width:5%;" type="text" value="" name="soc7">
                  <input style="width:5%;" type="text" value="" name="soc8">
                  <input style="width:5%;" type="text" value="" name="soc9">
                </td>
              </tr>
            </table>
          </td>
      </tr>
      <tr>
        <td>
          <table>
            <tr>
              <td width="10%">City:</td>
              <td width="10%"><input class="form-control" type="text" value="" name="city"></td>
              <td width="10%" style="padding-left:25px">Province:</td>
              <td width="10%"><input class="form-control" type="text" value="" name="province"></td>
              <td width="10%" style="padding-left:25px">Postal Code:</td>
              <td width="10%"><input class="form-control" type="text" value="" name="postal_code"></td>
              <td width="10%" style="padding-left:25px">Personal Health #:</td>
              <td width="30%">
                <input style="width:5%;" type="text" value="" name="ph1">
                <input style="width:5%;" type="text" value="" name="ph2">
                <input style="width:5%;" type="text" value="" name="ph3">
                <input style="width:5%;" type="text" value="" name="ph4">
                <input style="width:5%;" type="text" value="" name="ph5"> -
                <input style="width:5%;" type="text" value="" name="ph6">
                <input style="width:5%;" type="text" value="" name="ph7">
                <input style="width:5%;" type="text" value="" name="ph8">
                <input style="width:5%;" type="text" value="" name="ph9">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
            <table>
              <tr>
                <td width="9.5%">Phone Number:</td>
                <td width="47%"><input class="form-control" type="text" value="" name="phone_number"></td>
                <td width="10%" style="padding-left:25px">Date of Birth:</td>
                <td width="10%">
                  <input type="text" class="datepicker" name="dob">
                </td>
                <td width="8%" style="padding-left:25px">Gender</td>
                <td width="7%">
                  <input type="radio" class="form-control" name="gender" value="male">M
                  <input type="radio" class="form-control" name="gender" value="female">F
                </td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
                <td width="10%">Occupation:</td>
                <td width="20%"><input class="form-control" type="text" value="" name="occupation"></td>
                <td width="10%" style="padding-left:25px">Job description:</td>
                <td width="20%"><input class="form-control" type="text" value="" name="job_description"></td>
                <td width="10%" style="padding-left:25px">Date hired:</td>
                <td width="20%"><input class="datepicker" type="text" value="" name="date_hired"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="30%">Does the worker have WCB personal coverage with this business?</td>
              <td width="10%">
                <input type="radio" class="form-control" name="wcb_personal" value="yes">Yes
                <input type="radio" class="form-control" name="wcb_personal" value="no">No
              </td>
              <td width="30%">Is the worker a partner or director in this business?</td>
              <td width="10%">
                <input type="radio" class="form-control" name="partner" value="yes">Yes
                <input type="radio" class="form-control" name="partner" value="no">No
              </td>

            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table>
            <tr>
              <td width="30%">Is the worker an apprentice?</td>
              <td width="10%">
                <input type="radio" class="form-control" name="apprentice" value="yes">Yes
                <input type="radio" class="form-control" name="apprentice" value="no">No
              </td>
              <td width="30%">If yes, date the worker would have obtained journeyman status:</td>
              <td width="10%">
                <input type="text" class="datepicker" name="journeyman">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="font-size:17px;"><b>Employer Details</b></td>
      </tr>
      <tr>
        <td>
          <table border="1" width="100%">
            <tr>
              <td width="50%">
                Business Name or Government Department: <br>
                <input type="text" class="form-control" name="business_name">
              </td>
              <td width="50%">
                <table border="0">
                  <tr>
                    <td width="20%">
                      WCB Account Number:
                    </td>
                    <td width="25%"><input type="text" name="wcb_account_number" class="form-control"></td>
                    <td width="20%" style="padding-left:45px">Industry</td>
                    <td width="30%">
                      <input style="width:18%;" type="text" value="" name="ind1">
                      <input style="width:18%;" type="text" value="" name="ind2">
                      <input style="width:18%;" type="text" value="" name="ind3">
                      <input style="width:18%;" type="text" value="" name="ind4">
                      <input style="width:18%;" type="text" value="" name="ind5">
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="50%">
                <table border="0" width="100%">
                  <tr>
                    <td width="30%">
                      Mailing Address:
                    </td>
                    <td width="70%"><input type="text" class="form-control" name="employer_mailing_address"></td>
                  </tr>
                </table>
              </td>
              <td rowspan="2" width="50%">
                <b>2</b> Employer/Supervisor Contact Name and Title: <br> <br>
                <input type="text" class="form-control" name="employer_contact_name">
              </td>
            </tr>
            <tr>
              <td width="50%">
                <table border="0" width="100%">
                  <tr>
                    <td width="30%">
                    City:
                    </td>
                    <td width="70%"><input type="text" class="form-control" name="employer_city"></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="50%">
                <table border="0">
                  <tr>
                    <td width="20%">
                      Province:
                    </td>
                    <td width="25%"><input type="text" name="employer_province" class="form-control"></td>
                    <td width="20%" style="padding-left:45px">Postal Code:</td>
                    <td width="25%"><input type="text" name="employer_post_code" class="form-control"></td>
                  </tr>
                </table>
              </td>
              <td width="50%">
                <table border="0" width="100%">
                  <tr>
                    <td width="30%">
                    Contact Phone:
                    </td>
                    <td width="70%"><input type="text" class="form-control" name="pemployer_contact_phone"></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="50%">
                <table border="0">
                  <tr>
                    <td width="20%">
                      Phone:
                    </td>
                    <td width="25%"><input type="text" name="employer_phone" class="form-control"></td>
                    <td width="20%" style="padding-left:45px">Fax:</td>
                    <td width="25%"><input type="text" name="employer_fax" class="form-control"></td>
                  </tr>
                </table>
              </td>
              <td width="50%">
                <table border="0" width="100%">
                  <tr>
                    <td width="30%">
                    Contact E-mail:
                    </td>
                    <td width="70%"><input type="text" class="form-control" name="employer_email"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="font-size:17px;"><b>Accident Details</b></td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1">
            <tr>
              <td width="80%">
                <table width="100%" border="0">
                  <tr>
                    <td width="50%">
                      <b> &nbsp; 3 </b> Date/time of accident:
                    </td>
                    <td width="25%"><input type="text" class="form-control datepicker" name="accident_date" class="form-control"></td>
                    <td width="25%"><input type="text" class="form-control datetimepicker" name="accident_time" class="form-control"></td>
                  </tr>
                </table>
              </td>
              <td rowspan="3"> <input type="radio" name="injury_overtime" value="Overtime injury">
                the injury/condition<br>
                developed over time
            </tr>
            <tr>
              <td width="80%">
                <table width="100%" border="0">
                  <tr>
                    <td width="50%">
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date/time scheduled shift started:
                    </td>
                    <td width="25%"><input type="text" class="form-control datepicker" name="shift_start_date" class="form-control"></td>
                    <td width="25%"><input type="text" class="form-control datetimepicker" name="shift_start_time" class="form-control"></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="80%">
                <table width="100%" border="0">
                  <tr>
                    <td width="50%">
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date/time scheduled shift ended:
                    </td>
                    <td width="25%"><input type="text" class="form-control datepicker" name="shift_end_date" class="form-control"></td>
                    <td width="25%"><input type="text" class="form-control datetimepicker" name="shift_end_time" class="form-control"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="80%">
            <tr>
              <td width="30%"><b> &nbsp; 4 </b> Date accident/injury reported to employer:</td>
              <td width="30%"><input type="text" class="form-control datepicker" name="accident_reported_date" class="form-control"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table  width="100%">
            <tr>
              <td width="25%">To whom was the accident/injury reported?:</td>
              <td width="25%"><input type="text" class="form-control" value="" name="reported_person"></td>
              <td width="25%" style="padding-left:45px">Phone Number</td>
              <td width="25%"><input type="text" class="form-control" value="" name="reported_person_number"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td><b> &nbsp; 5 </b> Describe fully, based on the information you have, what happened to cause this injury or disease. Please describe what the worker was doing, including details
                <br> about any tools, equipment, materials, etc., the worker was using. State any gas, chemicals or extreme temperatures worker may have been exposed to:
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="100%">
                <input type="text" class="form-control" value="" name="detail1">
                <input type="text" class="form-control" value="" name="detail2">
                <input type="text" class="form-control" value="" name="detail3">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1">
            <tr>
              <td width="70%">
                <table width="100%" border="0">
                  <tr>
                    <td>Motor vehicle accident?</td>
                    <td>
                        <input type="radio" class="form-control" name="motor" value="yes">Yes
                        <input type="radio" class="form-control" name="motor" value="no">No
                    </td>
                    <td>Cardiac condition/injury?</td>
                    <td>
                        <input type="radio" class="form-control" name="cardiac_injury" value="yes">Yes
                        <input type="radio" class="form-control" name="cardiac_injury" value="no">No
                    </td>
                  </tr>
                </table>
              </td>
              <td width="30%">If you have more information, please attach a letter. Letter attached?<br>
                <input type="radio" class="form-control" name="attach_letter" value="yes">Yes
                <input type="radio" class="form-control" name="attach_letter" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="50%">Did the accident/injury occur on employer’s premises?</td>
              <td width="50%">
                <input type="radio" class="form-control" name="employer_premises" value="yes">Yes
                <input type="radio" class="form-control" name="employer_premises" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table>
            <tr>
              <td><b> &nbsp; 6 </b> Location where the accident happened (address, general location or site): &nbsp;&nbsp;&nbsp;</td>
              <td>
                <input type="text" class="form-control" name="location">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="50%">Were the worker’s actions at the time of injury for the purpose of your business?</td>
              <td width="50%">
                <input type="radio" class="form-control" name="purpose_of_business" value="yes">Yes
                <input type="radio" class="form-control" name="purpose_of_business" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="50%">Were the actions part of the worker’s regular duties?</td>
              <td width="50%">
                <input type="radio" class="form-control" name="regular_duties" value="yes">Yes
                <input type="radio" class="form-control" name="regular_duties" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table border="1" width="100%">
            <tr>
              <td width="10%" style="font-size:17px;"><b>Injury Details</b></td>
              <td width="90%">What part of body was injured? (hand, eye, back, lungs, etc.)
                <input type="radio" class="form-control" name="body_type" value="left">Left Side
                <input type="radio" class="form-control" name="body_type" value="right">Right Side
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="30%">What type of injury is this? (sprain, strain, bruise, etc.)</td>
              <td width="70%"><input type="text" class="form-control" value="" name="type_of_injury"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="60%"><?php include ('../phpsign/sign.php'); ?></td>
              <td width="40%" style="float:right;margin-top:80px">Date <input type="text" style="width:80%;display:inline" class="form-control datepicker" name="claim_date"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td style="text-align:right">If you have any other information that would help us make a decision, or if you have concerns, please attach a letter.
                  <br><b>THIS DOCUMENT MAY BE EXAMINED BY ANY PERSON WITH A DIRECT INTEREST IN A CLAIM THAT IS UNDER REVIEW OR APPEAL.</b></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br><br><br><br>
    <b>PAGE 2 </b>
    <br>
    <table border="1" width="100%">
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td>EMPLOYER REPORT</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td>
                <table width="100%">
                  <tr>
                      <td width="15%">Worker's Last Name:</td>
                      <td width="20%"><input class="form-control" type="text" value="" name="worker_last_name"></td>
                      <td width="15%" style="padding-left:25px">Worker's First Name:</td>
                      <td width="25%"><input class="form-control" type="text" value="" name="worker_first_name"></td>
                      <td width="10%" style="padding-left:45px">Initial:</td>
                      <td width="5%"><input class="form-control" type="text" value="" name="worker_initial"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="10%">Social Insurance #:</td>
              <td width="40%">
                <input style="width:5%;" type="text" value="" name="worker_soc1">
                <input style="width:5%;" type="text" value="" name="worker_soc2">
                <input style="width:5%;" type="text" value="" name="worker_soc3">
                <input style="width:5%;" type="text" value="" name="worker_soc4">
                <input style="width:5%;" type="text" value="" name="worker_soc5">
                <input style="width:5%;" type="text" value="" name="worker_soc6">
                <input style="width:5%;" type="text" value="" name="worker_soc7">
                <input style="width:5%;" type="text" value="" name="worker_soc8">
                <input style="width:5%;" type="text" value="" name="worker_soc9">
              </td>
              <td width="10%" style="padding-left:25px">Date of Birth:</td>
              <td width="40%"><input type="text" class="form-control datepicker" name="worker_dob"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="font-size:17px;"><b> 7 &nbsp; Return to Work Details</b></td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="25%">a. Will/did you pay the worker regular pay while off work?</td>
              <td width="25%">
                <input type="radio" class="form-control" name="regular_pay" value="yes">Yes
                <input type="radio" class="form-control" name="regular_pay" value="no">No
              </td>
              <td width="25%">Has the worker returned to work?</td>
              <td width="25%">
                <input type="radio" class="form-control" name="has_returned" value="yes">Yes
                <input type="radio" class="form-control" name="has_returned" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td>b. Date and time worker first missed work:</td>
              <td width="25%"><input type="text" class="form-control datepicker" name="missed_work_date" class="form-control"></td>
              <td width="25%"><input type="text" class="form-control datetimepicker" name="missed_work_time" class="form-control"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td>c. If the worker has returned to work, indicate date:</td>
              <td width="25%"><input type="text" class="form-control datepicker" name="return_work_date" class="form-control"></td>
              <td width="25%"><input type="text" class="form-control datetimepicker" name="return_work_time" class="form-control"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="15%">Current work status:</td>
              <td width="15%"><input type="radio" class="form-control" name="current_work_status" value="Regular work duties">Regular work duties, or</td>
              <td width="15%"><input type="radio" class="form-control" name="current_work_status" value="Modified work duties">Modified work duties</td>
              <td width="15%"><input type="radio" class="form-control" name="current_work_status_hours" value="Regular hours of work, or">Regular hours of work, or</td>
              <td width="15%"><input type="radio" class="form-control" name="current_work_status_hours" value="Modified hours of work">Modified hours of work</td>
              <td width="25%"><input type="text" name="modified_hours" style="width:10%"> hrs per <input type="text" name="modified_per" style="width:10%">
            </tr>
            <tr>
              <td width="15%">&nbsp;</td>
              <td width="15%"><input type="radio" class="form-control" name="current_work_status_rate" value="Pre-accident rate of pay, or">Pre-accident rate of pay, or</td>
              <td width="15%"><input type="radio" class="form-control" name="current_work_status_rate" value="Revised rate of pay">Revised rate of pay: $</td>
              <td width="15%"><input type="text" name="regular_hours" style="width:20%"> per <input type="text" name="regular_per" style="width:20%">
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td>If the worker is working modified duties, please describe:</td>
              <td><input type="text" class="form-control" name="modified_duties" class="form-control"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="60%">d. If the worker is not back at work are you able to modify work duties/hours to accommodate an early return?</td>
              <td width="10%"><input type="radio" class="form-control" name="accommodate" value="yes" class="form-control">Yes</td>
              <td width="10%"><input type="radio" class="form-control" name="accommodate" value="no" class="form-control">No</td>
              <td width="20%"><input type="radio" class="form-control" name="accommodate" value="was_offered" class="form-control">Was offered but the worker declined</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="25%">e. Approximate return to work date:</td>
              <td width="25%"><input type="text" class="form-control datepicker" name="approx_return_date" class="form-control"></td>
              <td width="25%" style="padding-left:25px">Does the worker have more than one position at your company?</td>
              <td width="10%"><input type="radio" class="form-control" value="yes" name="position_number" class="form-control">Yes</td>
              <td width="10%"><input type="radio" class="form-control" value="no" name="position_number" class="form-control">No</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="font-size:17px;"><b> 8 &nbsp; Employment Type Details (Complete A or B or C. Select the worker’s type of employment.)</b></td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">
                <b> A </b>
              </td>
              <td width="45%"> <input type="radio" class="form-control" name="employment_type" value="permanent">
                Permanent position employed 12 months of the year:
              </td>
              <td>
                <input type="radio" class="form-control" name="permanent_type" value="Full Time">Full Time
                <input type="radio" class="form-control" name="permanent_type" value="Part Time">Part Time
                <input type="radio" class="form-control" name="permanent_type" value="Irregular/Casual">Irregular/Casual
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">
                <b> or B </b>
              </td>
              <td width="45%"> <input type="radio" class="form-control" name="employment_type" value="non-permanent">
                Non-permanent position employed only part of the year (subject to seasonal or lack of work layoffs):
              </td>
              <td>
                <input type="radio" class="form-control" name="non_permanent_type" value="Seasonal worker">Seasonal worker
                <input type="radio" class="form-control" name="non_permanent_type" value="Summer Student">Summer Student
                <input type="radio" class="form-control" name="non_permanent_type" value="Temporary">Temporary
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">
                <b> &nbsp; </b>
              </td>
              <td width="15%">Position start date:</td>
              <td width="20%"><input type="text" class="form-control datepicker" name="position_start_date" class="form-control"></td>
              <td width="20%" style="padding-left:25px">Position end date:</td>
              <td width="20%"><input type="text" class="form-control datepicker" name="position_end_date" class="form-control"></td>
              <td width="20%" style="padding-left:25px">
                <input type="radio" class="form-control" name="position_type" value="Estimated">Estimated
                <input type="radio" class="form-control" name="position_type" value="Actual">Actual
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">
                <b> &nbsp; </b>
              </td>
              <td width="45%">How many months or days per year do you employ workers in this position?</td>
              <td idth="50%"><input type="text" class="form-control" name="number_of_months"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">
                <b> or C </b>
              </td>
              <td width="45%">
                Alternate employment:
              </td>
              <td>
                <input type="radio" class="form-control" name="alternate_employment" value="Sub contractor">Sub contractor
                <input type="radio" class="form-control" name="alternate_employment" value="Piece work">Piece work
                <input type="radio" class="form-control" name="alternate_employment" value="Vehicle owner/operator">Vehicle owner/operator
                <input type="radio" class="form-control" name="alternate_employment" value="Welder owner/operator">Welder owner/operator
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="50%">&nbsp;
              </td>
              <td>
                <input type="radio" class="form-control" name="alternate_employment" value="Self-employed">Self-employed
                <input type="radio" class="form-control" name="alternate_employment" value="Volunteer">Volunteer
                <input type="radio" class="form-control" name="alternate_employment" value="Commission">Commission
                <input type="radio" class="form-control" name="alternate_employment" value="Other">Other
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">
                <b> &nbsp; </b>
              </td>
              <td width="45%">Does the worker incur expenses to perform the work (substantial materials, heavy equipment, larger tools, etc.)?</td>
              <td width="50%">
                <input type="radio" class="form-control" name="incur_expense" value="yes">Yes
                <input type="radio" class="form-control" name="incur_expense" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">
                <b> &nbsp; </b>
              </td>
              <td width="25%">Will the worker receive a T4??</td>
              <td width="80%">
                <input type="radio" class="form-control" name="receive_t4" value="yes">Yes
                <input type="radio" class="form-control" name="receive_t4" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1">
            <tr>
              <td width="30%" style="font-size:17px;"><b> 9 &nbsp; Earnings Details</b></td>
              <td width="20%">Earnings information contact name (please print):</td>
              <td width="50%"><input type="text" name="earning_contact_name" class="form-control"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Earnings contact phone number:</td>
              <td width="25%"><input type="text" name="earning_contact_phone" class="form-control"></td>
              <td width="25%" style="padding-left:25px">Earnings contact e-mail:</td>
              <td width="25%"><input type="text" name="earning_contact_email" class="form-control"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Choose A or B</b></td>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>A</b></td>
              <td width="30%">Gross earnings for the period of one year prior to the date<br>
              of injury or date the worker was hired if less than one year:</td>
              <td width="15%"><input type="text" name="gross_earning" class="form-control"></td>
              <td width="2%" style="padding-left:25px">From</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="gross_start_date"></td>
              <td width="2%" style="padding-left:25px">To</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="gross_end_date"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="45%">Was any time missed from work without pay during the above period, excluding vacation? (eg. maternity, sick, WCB benefits)</td>
              <td width="50%">
                <input type="radio" class="form-control" name="time_missed" value="yes">Yes
                <input type="radio" class="form-control" name="time_missed" value="no">No
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="45%">Dates and reasons:</td>
              <td width="50%">
                <input type="text" class="form-control" name="date_n_reason">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>or B</b></td>
              <td width="35%"> Worker’s hourly rate of pay at time of accident: $ </td>
              <td width="60%"> <input type="text" class="form-control" name="hourly_rate">
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;</td>
              <td><b>Additional taxable benefits:</b>
                <br>
                Vacation Pay
                <input type="radio" name="vacation_pay" value="Taken as time off with pay">Taken as time off with pay OR
                <input type="radio" name="vacation_pay" value="Paid on a regular basis">Paid on a regular basis  %
                <input type="text" style="width:25%;display:inline" name="vacation_pay_amount" class="form-control">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="15%">Shift Premium Gross earnings:  $</td>
              <td width="25%"><input type="text" name="shift_earning" class="form-control"></td>
              <td width="5%" style="padding-left:25px">From</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="shift_earning_start_date"></td>
              <td width="4%" style="padding-left:45px">To</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="shift_earning_end_date"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="15%">Overtime Gross earnings:  $</td>
              <td width="25%"><input type="text" name="overtime_earning" class="form-control"></td>
              <td width="5%" style="padding-left:25px">From</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="overtime_start_date"></td>
              <td width="4%" style="padding-left:45px">To</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="overtime_end_date"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="15%">Other Gross earnings:  $</td>
              <td width="25%"><input type="text" name="other_earning" class="form-control"></td>
              <td width="5%" style="padding-left:25px">From</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="other_start_date"></td>
              <td width="4%" style="padding-left:45px">To</td>
              <td width="23%"><input type="text" class="form-control datepicker" name="other_end_date"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="font-size:17px;"><b> 10 &nbsp; Hours of Work Details</b></td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="15%">a. Number of hours (not including overtime):</td>
              <td><input type="text" name="number_of_hours" class="form-control"></td>
              <td width="50%">
                per
                <input type="radio" class="form-control" name="number_of_hours_per" value="Day">Day
                <input type="radio" class="form-control" name="number_of_hours_per" value="Week">Week
                <input type="radio" class="form-control" name="number_of_hours_per" value="Shift cycle">Shift cycle
                <input type="radio" class="form-control" name="number_of_hours_per" value="Other">Other <input type="text" class="form-control" style="width:25%;display:inline" name="number_of_hours_other">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="10%">b. Does the work schedule repeat?</td>
              <td width="85%" style="padding-left:50px">Date shift cycle commenced: &nbsp;&nbsp;&nbsp;<input type="text" name="commenced" class="form-control datepicker" style="width:25%;display:inline"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="10%">
                <input type="radio" name="repeat" class="form-control" value="No">&nbsp;No&nbsp;&nbsp;&nbsp;
                <img style="margin-top: 48px;margin-left: -34px;" src="../img/down_arrow.png" width="20" height="20">
                | &nbsp;&nbsp;&nbsp;<input type="radio" name="repeat" class="form-control" value="Yes">Yes
                <img style="margin-top: 10px;margin-left: 4px;" src="../img/side_arrow.png" width="20" height="20">
              </td>
              <td width="85%">
                <table>
                  <tr>
                    <td width="10%" style="padding-left:50px">Mark hours worked for one complete work schedule (use zero for days off):</td>
                    <td width="85%" style="padding-left:80px">
                      <table style="margin-left:50px" border="1" width="70%">
                          <tr>
                              <td width="13%">&nbsp;</td>
                              <td width="13%">Sun</td>
                              <td width="13%">Mon</td>
                              <td width="12%">Tues</td>
                              <td width="12%">Wed</td>
                              <td width="12%">Thur</td>
                              <td width="12%">Fri</td>
                              <td width="13%">Sat</td>
                          </tr>
                          <tr>
                              <td width="13%">Hours per day:</td>
                              <td width="13%"><input type="text" class="form-control" name="sun1"></td>
                              <td width="13%"><input type="text" class="form-control" name="mon1"></td>
                              <td width="12%"><input type="text" class="form-control" name="tue1"></td>
                              <td width="12%"><input type="text" class="form-control" name="wed1"></td>
                              <td width="12%"><input type="text" class="form-control" name="thu1"></td>
                              <td width="12%"><input type="text" class="form-control" name="fri1"></td>
                              <td width="13%"><input type="text" class="form-control" name="sat1"></td>
                          </tr>
                          <tr>
                              <td width="13%">Hours per day:</td>
                              <td width="13%"><input type="text" class="form-control" name="sun2"></td>
                              <td width="13%"><input type="text" class="form-control" name="mon2"></td>
                              <td width="12%"><input type="text" class="form-control" name="tue2"></td>
                              <td width="12%"><input type="text" class="form-control" name="wed2"></td>
                              <td width="12%"><input type="text" class="form-control" name="thu2"></td>
                              <td width="12%"><input type="text" class="form-control" name="fri2"></td>
                              <td width="13%"><input type="text" class="form-control" name="sat2"></td>
                          </tr>
                          <tr>
                              <td width="13%">Hours per day:</td>
                              <td width="13%"><input type="text" class="form-control" name="sun3"></td>
                              <td width="13%"><input type="text" class="form-control" name="mon3"></td>
                              <td width="12%"><input type="text" class="form-control" name="tue3"></td>
                              <td width="12%"><input type="text" class="form-control" name="wed3"></td>
                              <td width="12%"><input type="text" class="form-control" name="thu3"></td>
                              <td width="12%"><input type="text" class="form-control" name="fri3"></td>
                              <td width="13%"><input type="text" class="form-control" name="sat3"></td>
                          </tr>
                          <tr>
                            <td colspan="8">
                              or if your schedule is more than 21 days, attach a copy of the schedule.
                            </td>
                          </tr>
                      </table>
                    </td>
                    <td width="10%">
                      IMPORTANT Circle day of injury. See instructions
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="1">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="10%">
                Average regular
                hours worked per
                week(not including overtime)
                <input type="text" style="width:50%;display:inline" name="regular_repeat_hours" class="form-control">
              </td>
              <td width="85%">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
</body>
