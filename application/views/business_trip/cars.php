<!-- TABLE DATAGRID -->
<table id="dg" class="easyui-datagrid" style="width:100%;" toolbar="#toolbar">
  <thead>
    <tr>
      <th rowspan="2" field="ck" checkbox="true"></th>
      <th rowspan="2" data-options="field:'number',halign:'center',width:200">Plat No</th>
      <th rowspan="2" data-options="field:'location_name',width:150,halign:'center'">Location</th>
      <th rowspan="2" data-options="field:'type',width:150,halign:'center'">Type</th>
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
          <span style="width:35%; display:inline-block;">Plat No.</span>
          <input style="width:30%;" name="number" required="" class="easyui-textbox">
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Location</span>
        <input style="width:60%;" name="area_id" id="area_id" required="" class="easyui-combobox">
      </div>
      <div class="fitem">
          <span style="width:35%; display:inline-block;">Type</span>
          <select style="width:60%;" name="type" required="" panelHeight="auto" class="easyui-combobox">
              <option value="OPERATIONAL">OPERATIONAL</option>
              <option value="DELIVERY">DELIVERY</option>
          </select>
      </div>
      <div class="fitem">
        <span style="width:35%; display:inline-block;">Description</span>
        <input style="width:60%;" name="description" class="easyui-textbox">
      </div>
    </fieldset>
  </form>
</div>

<!-- PDF -->
<iframe id="printout" src="<?= base_url('business_trip/cars/print') ?>" style="width: 100%;" hidden></iframe>

<script>
  //ADD DATA
  function add() {
    $('#dlg_insert').dialog('open');
    url_save = '<?= base_url('business_trip/cars/create') ?>';
    $('#frm_insert').form('clear');
  }

  //EDIT DATA
  function update() {
    var row = $('#dg').datagrid('getSelected');
    if (row) {
      $('#dlg_insert').dialog('open');
      $('#frm_insert').form('load', row);
      url_save = '<?= base_url('business_trip/cars/update') ?>?id=' + btoa(row.id);
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
              url: '<?= base_url('business_trip/cars/delete') ?>',
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
    window.location.assign('<?= base_url('business_trip/cars/print/excel') ?>');
  }
  //RELOAD
  function reload() {
    window.location.reload();
  }

  $(function() {
    //SETTING DATAGRID EASYUI
    $('#dg').datagrid({
      url: '<?= base_url('business_trip/cars/datatables') ?>',
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

    $('#area_id').combobox({
      url: '<?= base_url('business_trip/areas/reads') ?>',
      valueField: 'id',
      textField: 'name',
      prompt: 'Choose Location',
      icons: [{
        iconCls: 'icon-clear',
        handler: function(e) {
          $(e.data.target).combobox('clear').combobox('textbox').focus();
        }
      }],
    });
  });

</script>