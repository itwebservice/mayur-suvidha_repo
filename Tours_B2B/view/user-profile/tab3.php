
  <h2 class="c-heading">
    Account Ledgers
  </h2>
  <!-- Table -->
  <div class="clearfix c-table st-dataTable">
    <div class="clearfix">
      <div class="row">
      <div class="col-md-8">
        <div class="formField">
            <label>Search By Date</label>
            <input type="text" id="from_date" onchange="acc_list_reflect();" class="txtBox d-inline-block wAuto" placeholder="From Date" />
            <input type="text" id="to_date" onchange="acc_list_reflect();" class="txtBox d-inline-block wAuto" placeholder="To Date" />
          </div>
        </div>
      </div>
    </div>
    <table class="table" id="acc_table"></table>
  </div>
  <!-- Table End -->