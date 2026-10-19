// Called by the searchable select when a tanod is picked from the dropdown.
// Fills the form fields from the matched Tanod List record (still editable).
function onTanodIdSearchSelect(optionEl, root) {
    const form = root.closest('[data-tanod-id-form]');
    if (!form) return;

    const map = {
        id_number: 'idNumber',
        full_name: 'fullName',
        address: 'address',
        date_of_birth: 'dateOfBirth',
        emergency_contact_name: 'ecName',
        emergency_contact_address: 'ecAddress',
        emergency_contact_number: 'ecNumber',
        emergency_contact_relationship: 'ecRelationship',
    };

    Object.entries(map).forEach(([fieldName, dataKey]) => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field) field.value = optionEl.dataset[dataKey] || '';
    });
}
window.onTanodIdSearchSelect = onTanodIdSearchSelect;