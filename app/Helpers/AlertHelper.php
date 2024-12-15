<?php

namespace Helpers;

class AlertHelper
{
    public static function showAlert($type, $title, $message, $timer = 3000)
    {
        return "
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: '{$type}',
                    title: '{$title}',
                    text: '{$message}',
                    showConfirmButton: false,
                    timer: {$timer}
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('success');
                        url.searchParams.delete('error');
                        window.history.replaceState({}, '', url);
                    }
                });
            </script>
        ";
    }
}
