:: go through every file in every folder, and run ffmpeg command for them to create low res preview
for /D %%D in (*) do (
    mkdir "%%D\preview" 2>nul
    mkdir "%%D\tinypreview" 2>nul
    mkdir "%%D\temp" 2>nul
    mkdir "%%D\tinytemp" 2>nul
    for %%G in ("%%D\*") do (
        ffmpeg -y -i "%%G" -qscale:v 1 -map_metadata 0 -movflags use_metadata_tags -vf "scale='min(3840,iw)':min'(1440,ih)':force_original_aspect_ratio=decrease:flags=lanczos" "%%D\temp\%%~nxG"
	"C:\Program Files\ImageMagick-7.1.0-Q8\magick" -verbose "%%D\temp\%%~nxG" -interlace plane -quality 60 "%%D\preview\%%~nxG"

        ffmpeg -n -i "%%G" -qscale:v 1 -map_metadata 0 -movflags use_metadata_tags -vf "scale='min(1280,iw)':min'(360,ih)':force_original_aspect_ratio=decrease:flags=lanczos" "%%D\tinytemp\%%~nxG"
	"C:\Program Files\ImageMagick-7.1.0-Q8\magick" -verbose "%%D\tinytemp\%%~nxG" -interlace plane -quality 30 "%%D\tinypreview\%%~nxG"
    )
    rd /s /q "%%D\tinytemp"
    rd /s /q "%%D\temp"
)
pause