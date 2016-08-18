
$(document).ready(function(){
	$('.toggle').on('click', function() {
	  $('.login-form').stop().addClass('active');
	});

	$('.close').on('click', function() {
	  $('.login-form').stop().removeClass('active');
	});

	$('.ui.star.rating')
	  .rating({
	    initialRating: 2,
	    maxRating: 4
	  });
		
	var data = [{ "empName": "test", "age": "67", "department": { "id": "1234", "name": "Sales" }, "author": "ravi"}];
	var source =
	{
	    datatype: "json",
	    datafields: [
	        { name: 'empName' },
	        { name: 'age' },
	        { name: 'id', map: 'department&gt;id' },
	        { name: 'name', map: 'department&gt;name' },
	        { name: 'author' }
	    ],
	    localdata: data
	};
    
    var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
        if (value < 20) {
            return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + value + '</span>';
        }
        else {
            return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #008000;">' + value + '</span>';
        }
    }
    var dataAdapter = new $.jqx.dataAdapter(source, {
        downloadComplete: function (data, status, xhr) { },
        loadComplete: function (data) { },
        loadError: function (xhr, status, error) { }
    });
    // initialize jqxGrid
    $("#jqxgrid").jqxGrid(
    {
        width: 850,
        source: dataAdapter, 
        theme : 'ui-redmond',               
        pageable: true,
        autoheight: true,
        sortable: true,
        altrows: true,
        enabletooltips: true,
        editable: true,
        selectionmode: 'multiplecellsadvanced',
        columns: [
          { text: 'empName', columngroup: 'ProductDetails', datafield: 'empName', width: 250 },
          { text: 'age', columngroup: 'ProductDetails', datafield: 'age', cellsalign: 'right', align: 'right', width: 200 },
          { text: 'author', columngroup: 'ProductDetails', datafield: 'author', align: 'right', cellsalign: 'right', cellsformat: 'c2', width: 200 },
          { text: 'id', datafield: 'id', cellsalign: 'right', cellsrenderer: cellsrenderer, width: 100 },
          { text: 'name', columntype: 'checkbox', datafield: 'name' }
        ],
        columngroups: [
            { text: 'Product Details', align: 'center', name: 'ProductDetails' }
        ]
    });
});