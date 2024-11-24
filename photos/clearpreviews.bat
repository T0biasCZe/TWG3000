:: go through every file in every folder, and run ffmpeg command for them to create low res preview
for /D %%D in (*) do (
    rd /s /q "%%D\tinytemp"
    rd /s /q "%%D\temp"
    rd /s /q "%%D\preview"
    rd /s /q "%%D\tinypreview"
)
pause