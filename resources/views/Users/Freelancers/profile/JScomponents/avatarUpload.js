const editAvatarBtn = document.getElementById('editAvatarBtn');
                const avatarInput = document.getElementById('avatarInput');

                editAvatarBtn.addEventListener('click', () => {
                    avatarInput.click();
                });

                avatarInput.addEventListener('change', (event) => {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            editAvatarBtn.previousElementSibling.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });