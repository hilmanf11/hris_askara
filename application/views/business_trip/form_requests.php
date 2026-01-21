<style>
  /* FORM UTAMA */
  .form-panel,
  .form-panel input,
  .form-panel textarea,
  .form-panel select,
  .form-panel .textbox-text,
  .form-panel .combobox,
  .form-panel .easyui-textbox,
  .form-panel .easyui-combobox,
  .form-panel .easyui-datebox {
    font-size: 14px !important;
  }

  /* LABEL */
  .form-panel span {
    font-size: 14px !important;
  }

  /* RADIO BUTTON */
  .form-panel .radiobutton-text {
    font-size: 14px !important;
  }


  .page-center {
    display: flex;
    justify-content: center;
  }

  .form-panel {
    width: 95%;
    /* kontrol lebar form */
    background: #fafafa;

  }

  .panel-header {
    width: 100% !important;
  }

  .form-panel .panel-inner {
    padding: 20px;
    box-sizing: border-box;
  }


  .form-header {
    position: relative;
    margin: 10px 0 15px;
  }

  .form-title {
    text-align: center;
    font-size: 25px !important;
    margin: 0;
  }

  .btn-back {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
  }

  .btn-add {
    text-align: right;
  }

  .employee-block {
    margin-bottom: 15px;
  }

  /* layout dasar */
  .fitem2 {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }

  .fitem2 span {
    width: 35%;
  }

  .field {
    width: 60%;
    position: relative;
  }

  /* ===== FIX EASYUI ===== */
  .field .textbox,
  .field .combo,
  .field .combogrid {
    width: 100% !important;
    position: relative !important;
  }

  /* PENTING: override width bawaan EasyUI */
  .field .textbox-addon {
    position: absolute !important;
    right: 0 !important;
    top: 0;
    height: 100%;
    width: auto !important;
    /* 🔥 INI KUNCINYA */
    display: flex;
    align-items: center;
  }

  /* icon dropdown */
  .field .textbox-addon .textbox-icon {
    width: 32px;
    height: 100%;
  }

  /* input text */
  .field .textbox-text {
    width: 100% !important;
    box-sizing: border-box;
    padding-right: 32px;
    /* ruang untuk icon */
  }


  .btn-remove {
    justify-content: flex-end;
  }

  /* ===== TOMBOL TAMBAH (HIJAU) ===== */
  .btn-add-green.l-btn {
    background: #28a745 !important;
    border-color: #28a745 !important;
    color: #fff !important;
  }

  .btn-add-green.l-btn:hover {
    background: #218838 !important;
  }

  /* ===== TOMBOL REMOVE (MERAH) ===== */
  .btn-remove-red.l-btn {
    background: #dc3545 !important;
    border-color: #dc3545 !important;
    color: #fff !important;
  }

  .btn-remove-red.l-btn:hover {
    background: #c82333 !important;
  }

  /* pastikan text muncul */
  .l-btn-text {
    display: inline !important;
  }

  .radio-group {
    display: inline-flex;
    gap: 20px;
    /* jarak antar pilihan */
  }

  .radio-item {
    display: inline-flex;
    align-items: center;
    min-width: 120px;
    /* kunci jarak supaya sejajar */
  }

  .btn-secondary {
    background: #D1D5DB !important;
    /* light gray */
    color: #111827 !important;
    /* dark text for contrast */
    border-radius: 8px;
    border: none;
    transition: background-color 0.2s ease;
  }

  .btn-secondary:hover {
    background-color: #9CA3AF !important;
    /* slightly darker gray */
  }
</style>
<!-- DIALOG SAVE AND UPDATE -->
<div class="form-header">
  <h1 class="form-title">FORM DINAS</h1>
</div>



<div class="page-center">
  <div class="form-panel" style="width:95%;padding:10px;">
    <form id="frm_insert1" method="post" enctype="multipart/form-data" novalidate>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Request No</span>
        <input style="width:60%;" name="request_no" id="request_no" disabled class="easyui-textbox" data-options="prompt:'Otomatis'">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Tanggal</span>
        <input style="width:60%;" name="date" id="date" required="" data-options="formatter:myformatter,parser:myparser" class="easyui-datebox">
      </div>
    </form>
  </div>
</div>

<div class="page-center">

  <div class="easyui-panel form-panel"
    title="Data Request Dinas"
    data-options="collapsible:true"
    style="width:95%;background:#fafafa;">
    <div class="panel-inner">
      <form id="frm_insert2" method="post" novalidate>

        <div class="fitem2">
          <span>Company</span>
          <div class="field">
            <input id="division_main" name="division_id" required>
          </div>
        </div>

        <hr>


        <!-- CONTAINER -->
        <div id="employee-container"></div>

        <!-- TEMPLATE (JANGAN DIPARSE EASYUI) -->
        <div id="employee-template" style="display:none">
          <div class="employee-block">

            <div class="fitem2">
              <span>Departement</span>
              <div class="field">
                <input class="departement" name="departement_id[]">
              </div>
            </div>

            <div class="fitem2">
              <span>Departement Sub</span>
              <div class="field">
                <input class="departement-sub" name="departement_sub_id[]">
              </div>
            </div>

            <div class="fitem2">
              <span>Nama</span>
              <div class="field">
                <input class="employee" name="employee_id[]">
              </div>
            </div>

            <div style="text-align:right;margin-top:10px;" class="btn-remove">
              <a href="javascript:void(0)"
                class="easyui-linkbutton btn-remove-red"
                onclick="removeEmployee(this)">
                Hapus
              </a>
            </div>

            <hr>
          </div>
        </div>

        <!-- TOMBOL TAMBAH -->
        <div style="text-align:right;margin-top:10px;">
          <a href="javascript:void(0)"
            class="easyui-linkbutton btn-add-green"
            onclick="addEmployee()">
            Tambah
          </a>
        </div>

      </form>
    </div>
  </div>
</div>

<div class="page-center">
  <div class="easyui-panel form-panel" title="Data Destination" data-options="collapsible:true"
    style="width:95%;background:#fafafa;">
    <div class="panel-inner">
      <form id="frm_insert3" method="post" novalidate>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Trip</span>
          <input class="easyui-radiobutton" name="trip_type" checked="true" value="1" data-options="onChange: toggleTrip"> One Trip &nbsp;
          <input class="easyui-radiobutton" name="trip_type" value="2" data-options="onChange: toggleTrip"> Round Trip
        </div>
      </form>
    </div>
  </div>
</div>

<div class="page-center" id="one_trip">
  <div class="easyui-panel form-panel" title="One Trip" data-options="collapsible:true"
    style="width:95%;background:#fafafa;">
    <div class="panel-inner">
      <form id="frm_insert4" method="post" enctype="multipart/form-data" novalidate>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Askara Group</span>
          <input class="easyui-radiobutton group_type" name="group_type" checked="true" value="GROUP" data-options="onChange: toggleGroupInput"> Yes &nbsp;
          <input class="easyui-radiobutton group_type" name="group_type" value="NON GROUP" data-options="onChange: toggleGroupInput"> No
        </div>
        <div id="group_yes">
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Kota Tujuan</span>
            <input style="width:60%;" name="area_id" id="area_id" required class="easyui-combobox">
          </div>
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Lokasi</span>
            <input style="width:60%;" name="location_id" id="location_id" required class="easyui-combobox">
          </div>
        </div>
        <div id="group_no">
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Kota Tujuan</span>
            <input style="width:60%;" name="area_text" id="area_text" class="easyui-textbox">
          </div>
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Lokasi</span>
            <input style="width:60%;" name="location_text" id="location_text" class="easyui-textbox">
          </div>
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Keperluan</span>
          <input style="width:60%; height:50px;" multiline="true" name="remarks" id="remarks" class="easyui-textbox">
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Tanggal & Jam Keberangkatan</span>
          <input style="width:30%;" name="date_in" id="date_in" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
          <input style="width:30%;" name="time_in" id="time_in" class="easyui-maskedbox" required mask="99:99:99" required>
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Tanggal & Jam Kepulangan</span>
          <input style="width:30%;" name="date_out" id="date_out" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser, editable:false">
          <input style="width:30%;" name="time_out" id="time_out" class="easyui-maskedbox" mask="99:99:99">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="page-center" id="round_trip">
  <div class="easyui-panel form-panel" title="Round Trip" data-options="collapsible:true"
    style="width:95%;background:#fafafa;">
    <div class="panel-inner">
      <form id="frm_insert5" method="post" enctype="multipart/form-data" novalidate>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Askara Group</span>
          <input class="easyui-radiobutton group_type2" name="group_type2" checked="true" value="GROUP" data-options="onChange: toggleGroupInput2"> Yes &nbsp;
          <input class="easyui-radiobutton group_type2" name="group_type2" value="NON GROUP" data-options="onChange: toggleGroupInput2"> No
        </div>
        <div id="group_yes2">
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Kota Tujuan</span>
            <input style="width:60%;" name="area_id2" id="area_id2" required class="easyui-combobox">
          </div>
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Lokasi</span>
            <input style="width:60%;" name="location_id2" id="location_id2" required class="easyui-combobox">
          </div>
        </div>
        <div id="group_no2">
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Kota Tujuan</span>
            <input style="width:60%;" name="area_text2" id="area_text2" class="easyui-textbox">
          </div>
          <div class="fitem">
            <span style="width:35%; display:inline-block;">Lokasi</span>
            <input style="width:60%;" name="location_text2" id="location_text2" class="easyui-textbox">
          </div>
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Keperluan</span>
          <input style="width:60%; height:50px;" multiline="true" name="remarks2" id="remarks2" class="easyui-textbox">
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Attachment</span>
          <input style="width:60%;" name="attachment" id="attachment" accept=".jpg, .png, .jpeg, .pdf, .heic" class="easyui-filebox" data-options="prompt: 'Max 2 Mb'">
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Lokasi Pengantaran Keberangkatan</span>
          <input style="width:60%;" name="departure_dropoff_location" id="departure_dropoff_location" class="easyui-textbox">
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Lokasi Penjemputan Keberangkatan</span>
          <input style="width:60%;" name="departure_pickup_location" id="departure_pickup_location" class="easyui-textbox">
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Tanggal & Jam</span>
          <input style="width:30%;" name="departure_date" id="departure_date" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
          <input style="width:30%;" name="departure_time" id="departure_time" required class="easyui-maskedbox" mask="99:99:99" required>
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Lokasi Penjemputan Kepulangan</span>
          <input style="width:60%;" name="return_pickup_location" id="return_pickup_location" class="easyui-textbox">
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Tanggal & Jam</span>
          <input style="width:30%;" name="return_date" id="return_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser, editable:false">
          <input style="width:30%;" name="return_time" id="return_time" class="easyui-maskedbox" mask="99:99:99">
        </div>
      </form>
    </div>
  </div>
</div>


<div class="page-center">
  <div class="easyui-panel form-panel" title="Kebutuhan Operational" data-options="collapsible:true"
    style="width:95%;background:#fafafa;">
    <div class="panel-inner">
      <form id="frm_insert6" method="post" enctype="multipart/form-data" novalidate>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Needed Deliver</span>
          <div class="radio-group">
            <div class="radio-item">
              <input class="easyui-radiobutton" name="delivery_required" value="1" checked> &nbsp; Yes
            </div>
            <div class="radio-item">
              <input class="easyui-radiobutton" name="delivery_required" value="0"> &nbsp; No
            </div>
          </div>
        </div>

        <div class="fitem">
          <span style="width:35%; display:inline-block;">Vehicle</span>
          <div class="radio-group">
            <div class="radio-item">
              <input class="easyui-radiobutton" name="vehicle_type" value="PRIVATE" checked> &nbsp; Pribadi
            </div>
            <div class="radio-item">
              <input class="easyui-radiobutton" name="vehicle_type" value="OPERATIONAL"> &nbsp; Operational
            </div>
          </div>
        </div>

        <div class="fitem">
          <span style="width:35%; display:inline-block;">E-Money</span>
          <div class="radio-group">
            <div class="radio-item">
              <input class="easyui-radiobutton" name="emoney_type" value="PRIVATE" checked> &nbsp; Pribadi
            </div>
            <div class="radio-item">
              <input class="easyui-radiobutton" name="emoney_type" value="OPERATIONAL"> &nbsp; Operational
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

<div class="page-center">
  <div class="easyui-panel form-panel" title="Delivery" data-options="collapsible:true"
    style="width:95%;background:#fafafa;">
    <div class="panel-inner">
      <form id="frm_insert7" method="post">
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Deliver</span>
          <input style="width:60%;" id="employee_delivery_id" name="employee_delivery_id" class="easyui-combobox">
        </div>
        <div class="fitem">
          <span style="width:35%; display:inline-block;">Plat No</span>
          <input style="width:60%;" id="plat_no" name="plat_no" class="easyui-combobox">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="page-center" style="margin:30px 0;">
  <div style="text-align:center;">
    <a href="javascript:void(0)"
      class="easyui-linkbutton"
      style="width:80px;padding:5px;margin-right:10px;border-radius: 8px;"
      onclick="saveForm()">
      SAVE
    </a>

    <a href="javascript:void(0)"
      class="easyui-linkbutton btn-secondary"
      style="width:80px;padding:5px;"
      onclick="goBack()">
      EXIT
    </a>
  </div>
</div>



<script>
  let isLoadingEmployee = false;
  let urlSave = ''; 
  const DETAIL = <?= isset($detail) ? json_encode($detail) : 'null' ?>;

  function goBack() {
    window.location.href = "<?= base_url('business_trip/business_trip_requests/index/') ?>" + '<?= $id_menu ?>';
  }
  $(function() {
    $("#group_yes").show();
    $("#group_no").hide();

    $("#group_yes2").show();
    $("#group_no2").hide();

    $(function() {
      toggleTrip(true);
    });

    $('#plat_no').combobox({
      url: '<?php echo base_url('business_trip/cars/reads'); ?>',
      valueField: 'number',
      textField: 'number',
      prompt: 'Choose All',
      icons: [{
        iconCls: 'icon-clear',
        handler: function(e) {
          $(e.data.target).combobox('clear').combobox('textbox').focus();
        }
      }],
    });

    $('#employee_delivery_id').combobox({
      url: '<?php echo base_url('employee/employees/readDelivery'); ?>',
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose All',
      icons: [{
        iconCls: 'icon-clear',
        handler: function(e) {
          $(e.data.target).combobox('clear').combobox('textbox').focus();
        }
      }]
    });


    $('#area_id').combobox({
      url: '<?= base_url('business_trip/areas/reads') ?>',
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose Area',
      icons: [{
        iconCls: 'icon-clear',
        handler: function(e) {
          $(e.data.target).combobox('clear').combobox('textbox').focus();
        }
      }],
      onSelect: function(area) {
        const type_group = $('input[name="group_type"]:checked').val();
        $('#location_id').combobox({
          url: '<?php echo base_url('business_trip/locations/reads'); ?>?area_id=' + area.id + '&type_group=' + type_group,
          valueField: 'id',
          textField: 'division_name',
          prompt: 'Choose All',
          icons: [{
            iconCls: 'icon-clear',
            handler: function(e) {
              $(e.data.target).combobox('clear').combobox('textbox').focus();
            }
          }],
        });

      }

    });

    $('#area_id2').combobox({
      url: '<?= base_url('business_trip/areas/reads') ?>',
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose Area',
      icons: [{
        iconCls: 'icon-clear',
        handler: function(e) {
          $(e.data.target).combobox('clear').combobox('textbox').focus();
        }
      }],
      onSelect: function(area) {
        const type_group = $('input[name="group_type2"]:checked').val();
        $('#location_id2').combobox({
          url: '<?php echo base_url('business_trip/locations/reads'); ?>?area_id=' + area.id + '&type_group=' + type_group,
          valueField: 'id',
          textField: 'division_name',
          prompt: 'Choose All',
          icons: [{
            iconCls: 'icon-clear',
            handler: function(e) {
              $(e.data.target).combobox('clear').combobox('textbox').focus();
            }
          }],
        });

      }

    });

    $('#division_main').combobox({
      url: '<?= base_url('employee/divisions/reads'); ?>',
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose Company',
      onSelect: function(div) {

        // reload SEMUA departement di block
        $('#employee-container .employee-block').each(function() {
          let dep = $(this).find('.departement');
          let sub = $(this).find('.departement-sub');

          dep.combobox('reload',
            '<?= base_url('employee/departements/reads'); ?>?division_id=' + div.id
          );

          dep.combobox('clear');
          sub.combobox('clear');
        });
      }
    });

    addEmployee(); // block pertama

    // ===== MODE UPDATE =====
    if (typeof DETAIL !== 'undefined' && DETAIL) {
      urlSave = '<?= base_url('business_trip/business_trip_requests/update/') ?>' + DETAIL.request.id;
      loadUpdateData(DETAIL);
    } else {
      urlSave = '<?= base_url('business_trip/business_trip_requests/create') ?>';
    }
  });

  function loadUpdateData(detail) {

    // =========================
    // FORM UTAMA
    // =========================
    $('#frm_insert1').form('load', {
      request_no: detail.request.request_no,
      date: detail.request.request_date,
    });
    $('#frm_insert2').form('load', {
      division_id: detail.request.division_id,
    });

    // =========================
    // EMPLOYEE
    // =========================

      isLoadingEmployee = true;

    detail.employees.forEach(function(emp, index) {

      if (index > 0) {
        addEmployee();
      }

      let block = $('#employee-container .employee-block').last();

      let departement = block.find('.departement');
      let sub = block.find('.departement-sub');
      let employee = block.find('.employee');

      // ===== LOAD DEPARTEMENT =====
      departement.combobox({
        onLoadSuccess: function() {

          departement.combobox('setValue', emp.departement_id);

          // ===== LOAD SUB =====
          sub.combobox({
            onLoadSuccess: function() {

              sub.combobox('setValue', emp.departement_sub_id);

              // ===== SET EMPLOYEE =====
              employee.combogrid('setValue', emp.employee_id);
            }
          });

          sub.combobox('reload',
            '<?= base_url('employee/departement_subs/reads'); ?>' +
            '?division_id=' + emp.division_id +
            '&departement_id=' + emp.departement_id
          );
        }
      });

      // trigger departement load
      departement.combobox('reload',
        '<?= base_url('employee/departements/reads'); ?>?division_id=' + emp.division_id
      );

    });

    // =========================
    // TRIP
    // =========================        
    if (detail.request.trip_type === 'ONE') {
      // $('input[name="trip_type"][value="1"]').radiobutton('check');
      $('#frm_insert3').form('load', {
        trip_type: '1'
      });
      toggleTrip();

      loadGroupType(detail.trip.group_type);

      if (detail.trip.group_type === 'GROUP') {

        $('#area_id').combobox('setValue', detail.trip.area_id);
        $('#location_id').combobox({
          onLoadSuccess: function() {
            $('#location_id').combobox('setValue', detail.trip.location_id);
          }
        });
      } else {
        $('#area_text').textbox('setValue', detail.trip.area_text);
        $('#location_text').textbox('setValue', detail.trip.location_text);
      }

      $('#remarks').textbox('setValue', detail.trip.remarks);
      $('#date_in').datebox('setValue', detail.trip.date_in);
      $('#time_in').maskedbox('setValue', detail.trip.time_in);
      $('#date_out').datebox('setValue', detail.trip.date_out);
      $('#time_out').maskedbox('setValue', detail.trip.time_out);

    } else {

      $('#frm_insert3').form('load', {
        trip_type: '2'
      });
      toggleTrip();

      loadGroupType(detail.trip.group_type);

      if (detail.trip.group_type === 'GROUP') {
       $('#area_id2').combobox('setValue', detail.trip.area_id);
        $('#location_id2').combobox({
          onLoadSuccess: function() {
            $('#location_id2').combobox('setValue', detail.trip.location_id);
          }
        });
      } else {
        $('#area_text2').textbox('setValue', detail.trip.area_text);
        $('#location_text2').textbox('setValue', detail.trip.location_text);
      }

      $('#remarks2').textbox('setValue', detail.trip.remarks);
      $('#departure_dropoff_location').textbox('setValue', detail.trip.departure_dropoff_location);
      $('#departure_pickup_location').textbox('setValue', detail.trip.departure_pickup_location);
      $('#departure_date').datebox('setValue', detail.trip.departure_date);
      $('#departure_time').maskedbox('setValue', detail.trip.departure_time);
      $('#return_pickup_location').textbox('setValue', detail.trip.return_pickup_location);
      $('#return_date').datebox('setValue', detail.trip.return_date);
      $('#return_time').maskedbox('setValue', detail.trip.return_time);
    }

    // =========================
    // OPERATIONAL
    // =========================
    $('#frm_insert6').form('load', {
      delivery_required: detail.request.delivery_required,
      vehicle_type: detail.request.vehicle_type,
      emoney_type: detail.request.emoney_type
    });

    $('#frm_insert7').form('load', {
      employee_delivery_id: detail.request.employee_delivery_id,
      plat_no: detail.request.plat_no,
    });
  }



  function initEmployeeBlock(block) {

    let departement = block.find('.departement');
    let departementSub = block.find('.departement-sub');
    let employee = block.find('.employee');

    // =========================
    // DEPARTEMENT
    // =========================
    departement.combobox({
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose Departement',
      panelHeight: 'auto',
      onSelect: function(dep) {

        let divisionId = $('#division_main').combobox('getValue');

        // reset sub & employee
        departementSub.combobox('clear');
        employee.combogrid('clear');

        departementSub.combobox('reload',
          '<?= base_url('employee/departement_subs/reads'); ?>' +
          '?division_id=' + divisionId +
          '&departement_id=' + dep.id
        );
      }
    });

    // =========================
    // DEPARTEMENT SUB
    // =========================
    departementSub.combobox({
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose Sub Departement',
      panelHeight: 'auto',
      onSelect: function(sub) {

        let divisionId = $('#division_main').combobox('getValue');
        let departementId = departement.combobox('getValue');

        // reset employee
        employee.combogrid('clear');

        employee.combogrid('grid').datagrid('load', {
          division_id: divisionId,
          departement_id: departementId,
          departement_sub_id: sub.id
        });
      }
    });

    // =========================
    // EMPLOYEE
    // =========================
    employee.combogrid({
      panelWidth: 450,
      idField: 'id',
      textField: 'name',
      mode: 'remote',
      fitColumns: true,
      prompt: 'Choose Employee',
      url: '<?= base_url('employee/employees/readEmployees'); ?>',
      queryParams: {
        division_id: '',
        departement_id: '',
        departement_sub_id: ''
      },
      columns: [
        [{
            field: 'number',
            title: 'Employee ID',
            width: 150
          },
          {
            field: 'name',
            title: 'Employee Name',
            width: 200
          }
        ]
      ]
    });
  }


  function addEmployee() {

    // ===== VALIDASI COMPANY =====
    let divisionId = $('#division_main').combobox('getValue');

      if (!isLoadingEmployee) {

    // ===== VALIDASI BLOCK TERAKHIR =====
    let lastBlock = $('#employee-container .employee-block').last();

    if (lastBlock.length) {
      let departement = lastBlock.find('.departement').combobox('getValue');
      let sub = lastBlock.find('.departement-sub').combobox('getValue');
      let employee = lastBlock.find('.employee').combogrid('getValue');

      if (!departement || !sub || !employee) {
        $.messager.alert(
          'Warning',
          'Lengkapi Departement, Sub Departement, dan Nama sebelum menambah data baru'
        );
        return;
      }
    }
  }

    // ===== BARU BOLEH TAMBAH =====
    let block = $('#employee-template .employee-block').clone();
    $('#employee-container').append(block);

    $.parser.parse(block);
    initEmployeeBlock(block);

    // reload departement sesuai company
    block.find('.departement').combobox('reload',
      '<?= base_url('employee/departements/reads'); ?>?division_id=' + divisionId
    );

    toggleRemoveButton();
  }


  function removeEmployee(btn) {
    $(btn).closest('.employee-block').remove();
    toggleRemoveButton();
  }

  function toggleRemoveButton() {
    let total = $('#employee-container .employee-block').length;
    total <= 1 ?
      $('#employee-container .btn-remove').hide() :
      $('#employee-container .btn-remove').show();
  }




  function removeEmployee(btn) {
    $(btn).closest('.employee-block').remove();
    toggleRemoveButton();
  }

  function toggleRemoveButton() {
    let total = $('#employee-container .employee-block').length;

    console.log('TOTAL REAL:', total);

    if (total <= 1) {
      $('#employee-container .btn-remove').hide();
    } else {
      $('#employee-container .btn-remove').show();
    }
  }


  function toggleGroupInput() {
    const type = $('input[name="group_type"]:checked').val();

    if (type === 'GROUP') {
      $('#group_yes').show();
      $('#group_no').hide();

      // REQUIRED ON (GROUP)
      $('#area_id').combobox({
        required: true
      });
      $('#location_id').combobox({
        required: true
      });

      // REQUIRED OFF (NON GROUP)
      $('#area_text').textbox({
        required: false
      });
      $('#location_text').textbox({
        required: false
      });

      // CLEAR NON ACTIVE
      $('#area_text').textbox('clear');
      $('#location_text').textbox('clear');

    } else if (type === 'NON GROUP') {
      $('#group_yes').hide();
      $('#group_no').show();

      // REQUIRED ON (NON GROUP)
      $('#area_text').textbox({
        required: true
      });
      $('#location_text').textbox({
        required: true
      });

      // REQUIRED OFF (GROUP)
      $('#area_id').combobox({
        required: false
      });
      $('#location_id').combobox({
        required: false
      });

      // CLEAR NON ACTIVE
      $('#area_id').combobox('clear');
      $('#location_id').combobox('clear');
    }
  }


  function toggleGroupInput2() {
    const val = $('input[name="group_type2"]:checked').val();

    if (val === 'GROUP') {
      $('#group_yes2').show();
      $('#group_no2').hide();

      // REQUIRED ON
      $('#area_id2').combobox({
        required: true
      });
      $('#location_id2').combobox({
        required: true
      });

      // REQUIRED OFF
      $('#area_text2').textbox({
        required: false
      });
      $('#location_text2').textbox({
        required: false
      });

      // CLEAR NON ACTIVE
      $('#area_text2').textbox('clear');
      $('#location_text2').textbox('clear');

    } else {
      $('#group_yes2').hide();
      $('#group_no2').show();

      // REQUIRED ON
      $('#area_text2').textbox({
        required: true
      });
      $('#location_text2').textbox({
        required: true
      });

      // REQUIRED OFF
      $('#area_id2').combobox({
        required: false
      });
      $('#location_id2').combobox({
        required: false
      });

      // CLEAR NON ACTIVE
      $('#area_id2').combobox('clear');
      $('#location_id2').combobox('clear');
    }
  }







  //Format Datepicker
  function myformatter(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
  }

  //Format Datepicker
  function myparser(s) {
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[2], 10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
      return new Date(y, m - 1, d);
    } else {
      return new Date();
    }
  }

  function toggleTrip(checked) {
    if (!checked) return;

    let tripType = $('input[name="trip_type"]:checked').val();

    if (tripType === '1') {
      $('#one_trip').show();
      $('#round_trip').hide();

      $('#frm_insert5').form('disableValidation');
      $('#frm_insert4').form('enableValidation');
    } else {
      $('#one_trip').hide();
      $('#round_trip').show();

      $('#frm_insert4').form('disableValidation');
      $('#frm_insert5').form('enableValidation');
    }

  }

  function saveForm() {

    // ===== VALIDASI FORM UTAMA =====
    if (!$('#frm_insert1').form('validate')) {
      $.messager.alert('Warning', 'Form belum lengkap');
      return;
    }

    if (!$('#frm_insert3').form('validate')) {
      $.messager.alert('Warning', 'Form belum lengkap');
      return;
    }

    // ===== TRIP TYPE =====
    const tripType = $('input[name="trip_type"]:checked').val(); // 1 / 2

    // ===== DATA UTAMA =====
    let data = {
      request_no: $('#request_no').textbox('getValue'),
      request_date: $('#date').datebox('getValue'),
      trip_type: tripType == 1 ? 'ONE' : 'ROUND',
      division_id: $('#division_main').combobox('getValue'),
      delivery_required: $('input[name="delivery_required"]:checked').val(),
      vehicle_type: $('input[name="vehicle_type"]:checked').val(),
      emoney_type: $('input[name="emoney_type"]:checked').val(),
      employee_delivery_id: $('#employee_delivery_id').combobox('getValue'),
      plat_no: $('#plat_no').combobox('getValue')
    };

    // ===== EMPLOYEE LIST =====
    data.employees = [];
    $('#employee-container .employee-block').each(function() {
      data.employees.push({
        division_id: $('#division_main').combobox('getValue'),
        departement_id: $(this).find('.departement').combobox('getValue'),
        departement_sub_id: $(this).find('.departement-sub').combobox('getValue'),
        employee_id: $(this).find('.employee').combobox('getValue')
      });
    });

    if (data.employees[0].employee_id === "") {
      $.messager.alert('Warning', 'Minimal 1 employee');
      return;
    }

    // ===== DESTINATION =====
    if (tripType == 1) {
      if (!$('#frm_insert4').form('validate')) {
        $.messager.alert(
          'Warning',
          'Lengkapi data One Trip terlebih dahulu.'
        );
        return;
      }
      // ONE TRIP
      const groupType = $('input[name="group_type"]:checked').val();

      data.trip = {
        group_type: groupType,
        remarks: $('#remarks').textbox('getValue'),
        date_in: $('#date_in').datebox('getValue'),
        time_in: normalizeTime($('#time_in').val()),
        date_out: $('#date_out').datebox('getValue'),
        time_out: normalizeTime($('#time_out').val())
      };

      if (groupType === 'GROUP') {
        data.trip.area_id = $('#area_id').combobox('getValue');
        data.trip.location_id = $('#location_id').combobox('getValue');
      } else {
        data.trip.area_text = $('#area_text').textbox('getValue');
        data.trip.location_text = $('#location_text').textbox('getValue');
      }

    } else {
      if (!$('#frm_insert5').form('validate')) {
        $.messager.alert(
          'Warning',
          'Lengkapi data Round Trip terlebih dahulu.'
        );
        return;
      }
      // ROUND TRIP
      const groupType = $('input[name="group_type2"]:checked').val();

      data.trip = {
        group_type: groupType,
        remarks: $('#remarks2').textbox('getValue'),
        departure_dropoff_location: $('#departure_dropoff_location').textbox('getValue'),
        departure_pickup_location: $('#departure_pickup_location').textbox('getValue'),
        departure_date: $('#departure_date').datebox('getValue'),
        departure_time: normalizeTime($('#departure_time').val()),
        return_pickup_location: $('#return_pickup_location').textbox('getValue'),
        return_date: $('#return_date').datebox('getValue'),
        return_time: normalizeTime($('#return_time').val()),
      };

      if (groupType === 'GROUP') {
        data.trip.area_id = $('#area_id2').combobox('getValue');
        data.trip.location_id = $('#location_id2').combobox('getValue');
      } else {
        data.trip.area_text = $('#area_text2').textbox('getValue');
        data.trip.location_text = $('#location_text2').textbox('getValue');
      }
    }

    let formData = new FormData();
    formData.append('data', JSON.stringify(data));
    const fileInput = $('#attachment').next().find('input[type="file"]')[0];

    if (fileInput && fileInput.files.length > 0) {
      formData.append('attachment', fileInput.files[0]);
    }


    // ===== AJAX POST =====
    $.ajax({
      url: urlSave,
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: formData,
      processData: false, // WAJIB
      contentType: false, // WAJIB
      beforeSend: function() {
        $.messager.progress({
          text: 'Saving data...'
        });
      },
      success: function(res) {
        $.messager.progress('close');

        if (res.status) {
          $.messager.alert('Success', 'Data berhasil disimpan', 'info', function() {
            goBack();
          });
        } else {
          $.messager.alert('Error', res.message || 'Gagal menyimpan data');
        }
      },
      error: function() {
        $.messager.progress('close');
        $.messager.alert('Error', 'Terjadi kesalahan sistem, silakan hubungi administrator.');
      }
    });
  }

  function normalizeTime(val) {
    if (!val || val.indexOf('_') !== -1) {
      return null;
    }
    return val;
  }

  function loadGroupType(groupType) {
    $('#frm_insert4, #frm_insert5').form('load', {
      group_type: groupType
    });
  }
</script>