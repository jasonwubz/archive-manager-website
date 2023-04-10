<!-- reference: https://blog.logrocket.com/customizing-drag-drop-file-uploading-vue/ -->
<template>
    <div class="main">
        <div class="dropzone-container border border-primary" @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <div class="mt-4 justify-content-center text-center" v-if="files.length == 0">
                <h1>
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </h1>
                <input type="file" name="file" id="file-input" class="file-upload d-none" @change="onChange" ref="file" accept=".zip" />
                <label for="file-input" class="file-label" v-if="files.length == 0">
                    <div v-if="isDragging">Release to drop files here.</div>
                    <div v-else>Drop file here or <u role="button">click here</u> to upload.</div>
                </label>
            </div>

            <div class="mt-4 justify-content-center text-center" v-if="files.length">
                <div class="alert alert-secondary" role="alert" v-if="isUploading">
                    <div class="spinner-border text-info spinner-grow-sm" role="status">
                        <span class="sr-only"></span>
                    </div>
                    Uploading...
                </div>
                <div v-for="file in files" :key="file.name" class="container-fluid mb-4">
                    <div>
                        <strong>Filename:</strong>
                        {{ file.name }}
                        <i role="button" class="fa-sharp fa-solid fa-circle-xmark" @click.prevent="remove(files.indexOf(file))"></i>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-primary mb-2" :class="{'disabled' : isUploading}" @click.prevent="uploadFile()">Upload file</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isDragging: false,
            isUploading: false,
            files: [],
        };
    },
    methods: {
        uploadFile() {
            this.isUploading = true;
            const files = this.files;
            const formData = new FormData();
            files.forEach((file) => {
                formData.append("archive", file);
            });

            axios({
                method: "POST",
                url: "/api/v1/archive",
                data: formData,
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "multipart/form-data",
                },
            }).then((res) => {
                this.isUploading = false;
                console.log("uploaded!");
                this.$emit('file-uploaded');
                this.$toast.open(
                    {
                        message: "File uploaded successfully!",
                        duration: 5000,
                    }
                );
            })
            .catch((err) => {
                this.isUploading = false;
                console.log(err);
            });
        },
        onChange() {
            this.files = [...this.$refs.file.files];
        },
        remove(i) {
            this.files.splice(i, 1);
        },
        dragover(e) {
            e.preventDefault();
            this.isDragging = true;
        },
        dragleave() {
            this.isDragging = false;
        },
        drop(e) {
            e.preventDefault();
            this.$refs.file.files = e.dataTransfer.files;
            this.onChange();
            this.isDragging = false;
        },
  },
};
</script>
