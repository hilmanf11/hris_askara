<!-- TABLE DATAGRID -->
<table id="dg" class="easyui-datagrid" style="width:100%;" toolbar="#toolbar">
  <thead>
    <tr>
      <th rowspan="2" field="ck" checkbox="true"></th>
      <th rowspan="2" data-options="field:'number',halign:'center',width:200">Code</th>
      <th rowspan="2" data-options="field:'start_date',width:200,halign:'center'">Start Date</th>
      <th rowspan="2" data-options="field:'valid_date',width:120,halign:'center',align:'center'">Valid Until</th>
      <th rowspan="2" data-options="field:'category_name',width:150,halign:'center'">Expense Category</th>
      <th rowspan="2" data-options="field:'area_name',width:150,halign:'center'">Location</th>
      <th rowspan="2" data-options="field:'director_amount',width:120,align:'right'">Director</th>
      <th rowspan="2" data-options="field:'asst_manager_amount',width:150,align:'right'">Asst. Manager</th>
      <th rowspan="2" data-options="field:'manager_amount',width:150,align:'right'">Manager</th>
      <th rowspan="2" data-options="field:'leader_amount',width:120,align:'right'">Leader</th>
      <th rowspan="2" data-options="field:'staff_amount',width:120,align:'right'">Staff</th>
      <th rowspan="2" data-options="field:'operator_amount',width:120,align:'right'">Operator</th>
      <th rowspan="2" data-options="field:'description',width:150,halign:'center'">Description</th>
      <th colspan="2" data-options="field:'',width:100,halign:'center'"> Created</th>
      <th colspan="2" data-options="field:'',width:100,halign:'center'"> Updated</th>
    </tr>
    <tr>
      <th data-options="field:'created_by',width:100,align:'center'"> By</th>
      <th data-options="field:'created_date',width:150,align:'center'"> Date</th>
      <th data-options="field:'updated_by',width:100,align:'center'"> By</th>
      <th data-options="field:'updated_date',width:150,align:'center'"> Date</th>
    </tr>
  </thead>
</table>

<div id="toolbar" style="height: 35px;">
  <?= $button ?>
</div>

<!-- DIALOG SAVE AND UPDATE -->
<div id="dlg_insert" class="easyui-dialog" title="Add New" data-options="closed: true,modal:true" style="width: 550px; padding:10px; top: 20px;">
  <form id="frm_insert" method="post" novalidate>
    <fieldset style="width:100%; border:1px solid #d0d0d0; margin-bottom: 10px; border-radius:4px; float: left;">
      <legend><b>Form Data</b></legend>
      <div class="fitem">
          <span style="width:35%; display:inline-block;">Code</span>
          <input style="width:30%;" name="number" required="" class="easyui-textbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Start Date</span>
        <input style="width:60%;" name="start_date" id="start_date" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Valid Date</span>
        <input style="width:60%;" name="valid_date" id="valid_date" class="easyui-datebox" required data-options="formatter:myformatter,parser:myparser, editable:false">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Category</span>
        <input style="width:60%;" name="expense_category_id" id="expense_category_id" required="" class="easyui-combobox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Area</span>
        <input style="width:60%;" name="area_id" id="area_id" required="" class="easyui-combobox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Director</span>
        <input style="width:60%;" name="director_amount" id="director_amount" data-options="buttonText:'Rp', buttonAlign:'left'" class="easyui-numberbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Asst. Manager</span>
        <input style="width:60%;" name="asst_manager_amount" id="asst_manager_amount" data-options="buttonText:'Rp', buttonAlign:'left'" class="easyui-numberbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Manager</span>
        <input style="width:60%;" name="manager_amount" id="manager_amount" data-options="buttonText:'Rp', buttonAlign:'left'" class="easyui-numberbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Leader</span>
        <input style="width:60%;" name="leader_amount" id="leader_amount" data-options="buttonText:'Rp', buttonAlign:'left'" class="easyui-numberbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Staff</span>
        <input style="width:60%;" name="staff_amount" id="staff_amount" data-options="buttonText:'Rp', buttonAlign:'left'" class="easyui-numberbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Operator</span>
        <input style="width:60%;" name="operator_amount" id="operator_amount" data-options="buttonText:'Rp', buttonAlign:'left'" class="easyui-numberbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Description</span>
        <input style="width:60%;" name="description" class="easyui-textbox">
      </div>
    </fieldset>
  </form>
</div>

<!-- PDF -->
<iframe id="printout" src="<?= base_url('business_trip/expense_specifications/print') ?>" style="width: 100%;" hidden></iframe>

<script>
  //ADD DATA
  function add() {
    $('#dlg_insert').dialog('open');
    url_save = '<?= base_url('business_trip/expense_specifications/create') ?>';
    $('#frm_insert').form('clear');
  }

  //EDIT DATA
  function update() {
    var row = $('#dg').datagrid('getSelected');
    if (row) {
      $('#dlg_insert').dialog('open');
      $('#frm_insert').form('load', row);
      url_save = '<?= base_url('business_trip/expense_specifications/update') ?>?id=' + btoa(row.id);
    } else {
      toastr.warning("Please select one of the data in the table first!", "Information");
    }
  }

  //DELETE DATA
  function deleted() {
    var rows = $('#dg').datagrid('getSelections');
    if (rows.length > 0) {
      $.messager.confirm('Warning', 'Are you sure you want to delete this data?', function(r) {
        if (r) {
          for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            $.ajax({
              method: 'post',
              url: '<?= base_url('business_trip/expense_specifications/delete') ?>',
              data: {
                id: row.id
              },
              success: function(result) {
                var result = eval('(' + result + ')');
              },
              error: function(jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR.statusText);
                $.messager.alert("Error", jqXHR.statusText, 'error');
              },
              complete: function(data) {
                $('#dg').datagrid('reload');
              }
            });
          }
        }
      });
    } else {
      toastr.warning("Please select one of the data in the table first!", "Information");
    }
  }

  //PRINT PDF
  function pdf() {
    $("#printout").get(0).contentWindow.print();
  }
  //PRINT EXCEL
  function excel() {
    window.location.assign('<?= base_url('business_trip/expense_specifications/print/excel') ?>');
  }
  //RELOAD
  function reload() {
    window.location.reload();
  }

  $(function() {
    //SETTING DATAGRID EASYUI
    $('#dg').datagrid({
      url: '<?= base_url('business_trip/expense_specifications/datatables') ?>',
      pagination: true,
      rownumbers: true,
      fit: true,
      pageList: [20, 50, 100, 500, 1000],
      pageSize: 20,
      nowrap: false,
    }).datagrid('enableFilter');

    //SAVE DATA
    $('#dlg_insert').dialog({
      buttons: [{
        text: 'Save',
        iconCls: 'icon-ok',
        handler: function() {
          $('#frm_insert').form('submit', {
            url: url_save,
            onSubmit: function() {
              return $(this).form('validate');
            },
            success: function(result) {
              var result = eval('(' + result + ')');

              if (result.theme == "success") {
                toastr.success(result.message, result.title);
              } else {
                toastr.error(result.message, result.title);
              }

              $('#dlg_insert').dialog('close');
              $('#dg').datagrid('reload');
            }
          });
        }
      }]
    });

    $('#expense_category_id').combobox({
      url: '<?= base_url('business_trip/expense_categories/reads') ?>',
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose Category',
      icons: [{
        iconCls: 'icon-clear',
        handler: function(e) {
          $(e.data.target).combobox('clear').combobox('textbox').focus();
        }
      }],
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
    });

  });

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
</script>