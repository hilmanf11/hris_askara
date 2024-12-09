<div class="easyui-accordion" style="width:100%;">
    <div title="Click this to hide the filter" data-options="selected:true" style="padding:10px; background:#F4F4F4;">
        <!-- <div style="width: 100%; display: grid; grid-template-columns: auto auto auto; grid-gap: 5px; display: flex;"> -->
        <fieldset style="width: 60%; border:2px solid #d0d0d0; margin-bottom: 5px; margin-top: 5px; border-radius:4px;">
            <legend><b>Form Filter Data</b></legend>
            <div class="fitem">
                <span style="width:15%; display:inline-block;">Period</span>
                <input style="width:20%;" id="filter_month_start" value="<?= date("m") ?>" class="easyui-combobox" data-options="prompt:'Month'">
                <input style="width:10%;" id="filter_year_start" value="<?= date("Y") ?>" class="easyui-combobox" data-options="prompt:'Year'"> To
                <input style="width:20%;" id="filter_month_end" value="<?= date("m") ?>" class="easyui-combobox" data-options="prompt:'Month'">
                <input style="width:10%;" id="filter_year_end" value="<?= date("Y") ?>" class="easyui-combobox" data-options="prompt:'Year'">
            </div>
            <div class="fitem">
                <span style="width:15%; display:inline-block;"></span>
                <a href="javascript:;" class="easyui-linkbutton" onclick="filter()"><i class="fa fa-search"></i> Filter Data</a>
            </div>
        </fieldset>

        <?= $button ?>
    </div>
</div>

<div class="easyui-panel" title="Print Preview" style="width:100%;padding:10px;" data-options="fit:true">
    <iframe id="printout" src="" style="width: 100%; height:95%; border: 0;"></iframe>
</div>
<script>
    function reload() {
        window.location.reload();
    }

    function pdf() {
        $("#printout").get(0).contentWindow.print();
    }

    function filter() {
        var filter_month_start = $("#filter_month_start").combobox('getValue');
        var filter_year_start = $("#filter_year_start").combobox('getValue');
        var filter_month_end = $("#filter_month_end").combobox('getValue');
        var filter_year_end = $("#filter_year_end").combobox('getValue');

        if (filter_month_start == "" || filter_month_end == "") {
            toastr.warning("Please Choose Filter Date");
        } else {
            var url = "?filter_month_start=" + filter_month_start +
                "&filter_year_start=" + filter_year_start +
                "&filter_month_end=" + filter_month_end +
                "&filter_year_end=" + filter_year_end;

            $("#printout").contents().find('html').html("<center><br><br><br><b style='font-size:20px;'>Please Wait...</b></center>");
            $("#printout").attr('src', '<?= base_url('report/payroll_accounts/print') ?>' + url);
        }
    }

    function excel() {
        var filter_month_start = $("#filter_month_start").combobox('getValue');
        var filter_year_start = $("#filter_year_start").combobox('getValue');
        var filter_month_end = $("#filter_month_end").combobox('getValue');
        var filter_year_end = $("#filter_year_end").combobox('getValue');

        if (filter_month_start == "" || filter_month_end == "") {
            toastr.warning("Please Choose Filter Date");
        } else {
            var url = "?filter_month_start=" + filter_month_start +
                "&filter_year_start=" + filter_year_start +
                "&filter_month_end=" + filter_month_end +
                "&filter_year_end=" + filter_year_end;

            window.location.assign('<?= base_url('report/payroll_accounts/print/excel') ?>' + url);
        }
    }

    $(function() {
        $('#filter_month_start').combobox({
            url: '<?php echo base_url('report/payroll_accounts/readMonths'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Select Month',
        });

        $('#filter_year_start').combobox({
            url: '<?php echo base_url('report/payroll_accounts/readYears'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Select Year',
        });

        $('#filter_month_end').combobox({
            url: '<?php echo base_url('report/payroll_accounts/readMonths'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Select Month',
        });

        $('#filter_year_end').combobox({
            url: '<?php echo base_url('report/payroll_accounts/readYears'); ?>',
            valueField: 'id',
            textField: 'name',
            prompt: 'Select Year',
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