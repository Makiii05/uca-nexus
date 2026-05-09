# TASK TICKET

Use this section for new pages, modules, reports, buttons, or other features that do not modify an existing code path.

## Task Title


---

## Objective

- create a seeder, but no need to execute
- make changes on subject offering view when searching by prospectus 
- add coulm on student table
- changing studnet_type [old, new] in student table

---

## Expected Outcome

- create a seeder based on the data on .Databases\website.sql
- instead of just shosing a list of department in subject offering view -> search by prospectus -> dropdown of departments, only show the department that is currently logged in. that way, they dont have to select its own department, then show the active curriculumn under it instead of calling them APi, since the college dropdown is automatically selectedf the current department logged in.
- create new column called 'academic_year_admitted' in student table 
- when student is admitted, the academic_year_admitted data is based on the application academic year
- Student Type:
    -- all new admitted student are 'new' (implemented already)
    -- if subject is assigned to the student iin elistment, check if the student type is old, if old then proceed, if new, check if the student has a entry in student_subject with different academic term. that means that the student already enrolled in the previous academic term, so the student is now 'old'. if the student has no entry in student_subject with different academic term, then the student is still 'new' if the student type is already old, then proceed. 
---

## Additional Todo/Revision

- if the student already has a downpayment, it cant delete a subject (done). but also make that the department cant add new subject. so disabbled the add button if the student already has a downpayment. and make a label, "Downpayment is already paid, cant add/delete subject".

---

## Technical Requirements

- Follow existing project architecture
- Reuse existing components/services if possible
- Avoid unnecessary dependencies
- Maintain backward compatibility
- Keep UI consistent with current design

---

## Files Possibly Involved

-
-
-

---

## Database Changes

- [ ] No database changes
- [ ] Requires migration
- [ ] Requires seed update
- [ ] Requires model update

## Details:

---

## Agent Instructions

1. Analyze existing related files first
2. Preserve existing functionality
3. Implement minimal clean solution
4. Avoid rewriting unrelated code
5. Explain changes after completion

---

## Completion Format

After finishing, provide:

- Summary of changes
- Files modified
- Possible side effects
- Suggested improvements

---