var _add_button_html = '<button type="button" id="add-shop" class="btn btn-primary">Pridėti kavinę</button>';
var _remove_button_html = '<button type="button" class="remove-shop btn btn-danger">Panaikinti kavinę</button>';

var $shops_div, $add_button;

$(document).ready(function() {
    $shops_div = $("#dtr_dtrbundle_event_shops");
    $add_button = $("<div></div>").append(_add_button_html);

    $shops_div.append($add_button);

    configExistingShops();

    //To add a shop field
    $("#add-shop").click(function() {
        var _prototype = $shops_div.data('prototype');
        var _index = $shops_div.data('index');

        var $new_select_block = $("<div></div>").append(_prototype.replace(/__name__/g, _index));

        updateNewShopField($new_select_block.find("select"));

        $shops_div.data('index', _index + 1);
        $add_button.before($new_select_block);
    });

    //To remove a shop field
    $(document).on('click', '.remove-shop', function() {
        var $parent_div = $(this).closest("div");
        var _total_fields = $shops_div.data('index');
        var _current_index = getFieldIndex($parent_div.find("select"));

        $parent_div.remove();

        if(_current_index == _total_fields - 1) {
            $shops_div.data('index', _total_fields - 1);
        } else {
            configExistingShops();
        }
    });

    //To config cascading or onload existing shop fields
    function configExistingShops() {
        var $existing_selects = $shops_div.find("select");
        var _existing_selects_number = $existing_selects.length;
        var $select;

        $shops_div.data('index', _existing_selects_number);

        $existing_selects.each(function(index, select) {
            $select = $(select);

            changeSelectAttrs(index, $select);
            updateNewShopField($select);
        });
    }

    //Change <select> attr info from prototype due to cascading if needed
    function changeSelectAttrs(index, $select) {
        if(index != getFieldIndex($select)) {
            var _id_parts = $select.attr("id").split("_");
            var _name = $select.attr("name");

            _id_parts[_id_parts.length - 1] = index;

            $select.attr("id", _id_parts.join("_"));
            $select.attr("name", _name.replace(/[\d]/g, index));
        }
    }

    //Add remove button if needed
    function updateNewShopField($select) {
        var $outer_div = $select.closest('div');

        if($outer_div.find(".remove-shop").length == 0) {
            $outer_div.append(_remove_button_html);
        }
    }

    //Get <select> index
    function getFieldIndex($select) {
        var _id_parts = $select.attr("id").split("_");

        return parseInt(_id_parts[_id_parts.length - 1]);
    }
});
