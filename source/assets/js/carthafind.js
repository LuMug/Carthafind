$(document).ready(function() {
    var activeSystemClass = $('.list-group-item.active');
	
    $('#system-search').keyup(function() {
		var finder = this;
        var tableBody = $('.table-list-search tbody');
        var tableRowsClass = $('.table-list-search tbody tr');
        $('.search-sf').remove();
		
        tableRowsClass.each(function(i, val) {
            var rowText = $(val).text().toLowerCase();
            var inputText = $(finder).val().toLowerCase();
			
			$('.search-query-sf').remove();
            if(inputText != '')
            {
                tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Stai cercando: "'
				+ $(finder).val()
				+ '"</strong></td></tr>');
			}
			
			rowText += $(val).data("keywords");
			
            if(rowText.indexOf(inputText) == -1)
            {
                tableRowsClass.eq(i).hide();
			}
            else
            {
                tableRowsClass.eq(i).show();
			}
		});
		
        if(tableRowsClass.children(':visible').length == 0)
        {
            tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">Nessun risultato.</td></tr>');
		}
	});
	
	$('#authors-select').multiSelect({
		selectableHeader: "<div class='custom-header'>Alunni</div>",
		selectionHeader: "<div class='custom-header'>Autori</div>",
		afterSelect: function(values) {
			$('#authors').val($('#authors').val()+values+",");
		}
	});

	$('.project-container').click(function() {
		$("#doc_title").text($(this).data("title"));
		$("#doc_file").text($(this).data("file"));
		$("#doc_file").attr("href", $(this).data("file"));
	})

	var backSpace;
	var close = '<a class="close"></a>';
	var PreTags = $(".tagarea")
	.val()
	.trim()
	.split(" ");
	
	$(".tagarea").after('<ul class="tag-box form-control"></ul>');
	
	for (i = 0; i < PreTags.length; i++) {
		$(".tag-box").append('<li class="tags">' + PreTags[i] + close + "</li>");
	}
	
	$(".tag-box").append(
		'<li class="new-tag"><input class="input-tag" type="text"></li>'
	);
	
	$(".input-tag").bind("keydown", function(kp) {
		var tag = $(".input-tag")
		.val()
		.trim();
		$(".tags").removeClass("danger");
		
		if (tag.length > 0) {
			backSpace = 0;
			if (kp.keyCode == 32) {
				$(".new-tag").before('<li class="tags">' + tag + close + "</li>");
				$(".tagarea").val($('.tagarea').val().replace(/\s/g,'')+","+tag);
				$(this).val("");
			}
		} else {
			if (kp.keyCode == 8) {
				$(".new-tag")
				.prev()
				.addClass("danger");
				backSpace++;
				if (backSpace == 2) {
					$(".new-tag")
					.prev()
					.remove();
					backSpace = 0;
				}
			}
		}
	});
	
	$(".tag-box").on("click", ".close", function() {
		$(this)
		.parent()
		.remove();
		var str = $('.tagarea').val();
		var res = str.replace($(this).parent().get(0).textContent + ",", " ");
		res = res.replace("," + $(this).parent().get(0).textContent, "");
		res = res.replace($(this).parent().get(0).textContent, "");
		res = res.replace("," + $(this).parent().get(0).textContent + ",", "");
		$(".tagarea").val(res);
		console.log($(".tagarea").val());
	});
	
	$(".tag-box").click(function() {
		$(".input-tag").focus();
	});
	
	$(".tag-box").on("dblclick", ".tags", function(cl) {
		var tags = $(this);
		var tag = tags.text().trim();
		$(".tags").removeClass("edit");
		tags.addClass("edit");
		tags.html('<input class="input-tag" value="' + tag + '" type="text">');
		$(".new-tag").hide();
		tags.find(".input-tag").focus();
		
		tag = $(this)
		.find(".input-tag")
		.val();
		$(".tags").dblclick(function() {
			tags.html(tag + close);
			$(".tags").removeClass("edit");
			$(".new-tag").show();
		});
		
		tags.find(".input-tag").bind("keydown", function(edit) {
			tag = $(this).val();
			if (edit.keyCode == 70) {
				$(".new-tag").show();
				$(".input-tag").focus();
				$(".tags").removeClass("edit");
				if (tag.length > 0) {
					tags.html(tag + close);
					} else {
					tags.remove();
				}
			}
		});
	});

	$(function() {
		$(".tag-box").sortable({
			items: "li:not(.new-tag)",
			containment: "parent",
			scrollSpeed: 100
		});
		$(".tag-box").disableSelection();
	});
});

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode != 46 && (charCode < 48 || charCode > 57)))
		return false;
	return true;
}
	
		