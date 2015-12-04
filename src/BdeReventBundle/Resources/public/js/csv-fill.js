/*
 Take data from a CSV and fill an HTML table
 Copyright (C) 2015  Gabriel Augendre

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Data contained in the CSV file.
 * @typedef {Object} CSVLine
 * @property {string} mail - The person's mail
 * @property {string} firstName - The person's first name
 * @property {string} lastName - The person's last name
 */

/**
 * Parse CSV and put data in table.
 */
function parseCSV(filePath) {
    // Read file and then (callback) fill the table
    readFile(filePath, function (e) {
        var data = $.csv.toObjects(e.target.result);
        console.log(data);
        var table = $('#resultTable');
        table.empty();
        fillArray(data, table);
    });
    $('#sendDataButton').removeAttr('hidden');
}

/**
 * Fill the table with the given data.
 * @param {[CSVLine]} data Contains the lines of the CSV file.
 * @param table An HTML table, preferably empty. Title and data will be appended to it.
 */
function fillArray(data, table) {
    table.html("<tr><th>Nom</th><th>Prénom</th><th>Mail</th></tr>");
    var lastRow = table.find('tr:last');
    data.forEach(function (value, index) {
        lastRow.after('<tr>' +
        createCell('lastname', index, value.lastname, 'text') +
        createCell('firstname', index, value.firstname, 'text') +
        createCell('email', index, value.email, 'email') +
        '</tr>');
        lastRow = table.find('tr:last');
    });
}

function createCell(name, index, value, type) {
    return '<td>' + value + '</td>';
}

/**
 * read text input
 */
function readFile(filePath, onLoadCallback) {
    var reader = new FileReader();
    reader.onload = onLoadCallback;
    if (filePath.files && filePath.files[0]) {
        reader.readAsText(filePath.files[0]);
    }
}