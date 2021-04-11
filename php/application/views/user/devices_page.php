<div class="container mt-4 ">
	<table class="table table-hover">
		<thead>
		<tr>
			<th scope="col">Adı</th>
			<th scope="col">Mac</th>
			<th scope="col">Son Online Olduğu Tarih</th>
			<th scope="col">Online Durumu</th>
		</tr>
		</thead>
		<tbody>
		<tr class="table-success">
			<th scope="row">Success</th>
			<td>Column content</td>
			<td>Column content</td>
			<td>Column content</td>

		</tr>
		</tbody>
	</table>
</div>

<script>
	$(document).ready(function () {
		fetchData();

		function fetchData() {
			//$("tr[class='table-success']").remove();

			console.log("I am here!");
			$.getJSON("<?=base_url("api/devices/1/sifre1234")?>", function (data) {
				var items = [];
				console.log(data);
				$.each(data, function (key, val) {

					$.each(val, function (key, val) {
						if(key!=="online"){
							items.push("<td id='" + key + "'>" + val + "</td>");
						}
						else if(key=="online"){
							console.log("this is val "+ val);
							if(val==1){
								console.log("this is val for 1 "+ val);


								items.push("<td><span class='dot green'></span></td>");
							}else if(val==0){
								console.log("this is val for 0 "+ val);

								items.push("<td><span class='dot grey'></span></td>");
							}


						}


					});

				});

				/*	$("<tr/>", {
						"class": "table-success",
						html: items.join("")
					}).appendTo("tbody");*/

				//	$("tbody").replaceWith("<tr class='table-success'>"+items.join("")+"</tr>");
				$("tbody").empty().append("<tr class='table-success'>" + items.join("") + "</tr>");

			}).done(function () {

				setTimeout(fetchData, 10000);

				console.log("second success");
			});
		}

		//setInterval(fetchData, 10000);

	});


</script>

<style>
	.dot {
		height: 25px;
		width: 25px;
		border-radius: 50%;
		display: inline-block;
	}
	.grey {
		background-color: #bbb;
	}
	.green{
		background-color: green;
	}
</style>
