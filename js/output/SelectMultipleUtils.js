


export function createMultipleSelector(values) {
    var html = '<select class="mdb-select colorful-select dropdown-primary md-form" multiple searchable="Search here..">';
    html += '<option value="" disabled selected>Choose your country</option>';
    html += '<option value="1">USA</option>';
    html += '<option value="2">Germany</option>';
    html += '<option value="3">France</option>';
    html += '<option value="4">Poland</option>';
    html += '<option value="5">Japan</option>';
    html += '</select>';
    html += '<label class="mdb-main-label">Label example</label>';
    html += '<button class="btn-save btn btn-primary btn-sm">Save</button>';

    return html;
}






