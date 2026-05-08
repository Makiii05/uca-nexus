# TASK TICKET

Use this section for new pages, modules, reports, buttons, or other features that do not modify an existing code path.

## Task Title


---

## Objective

- to copy a feature/function from one module to another. then add a few tweaks or modifications to the copied feature/function.

---

## Expected Outcome

- copy the admission.official_students to registrar. name the copied official students to "official students" too.
- the copied feature have no import button.
- the copied feature has only the following action: view details, print, edit
- in view detail include the following info: Academic, Personal, COntact, family BG, Educational BG
- in view detail include too the profile picture of student on upper right. if no profile picture, show the add profile picture button. if with profile picture, show the profile picture. inline with that, create new FK table for profile picture of each student and call it student.profile_picture. the profile picture table include FK student_id and the uniqid() as the filename and the file itself stored in the assets folder. so when saving the pfp in local storage assets, it would save like : \assets\images\profile_picture\ {uniqid()}.jpg and display it in the view details modal at upper right. also, add change and delete pfp under the profile picture in view details modal. and when do delete, delete too in the asset. and when edditing, just replace the old file with new file in the same folder with the same id.
- the printing includes the pfp, placeholder if no pfp, in the upper right, below the header info.
- do not remove the official student in admssion

---

## Additional Todo

- 

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