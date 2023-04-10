<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Archive Manager</h1>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Archive Listing
                        <button type="button" class="btn btn-primary float-right" v-b-modal.fileUploadModal>Upload</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Filename</th>
                                        <!-- <th scope="col">Uploaded By</th> -->
                                        <th scope="col">Size</th>
                                        <th scope="col" class="d-none d-lg-table-cell">MD5 Checksum</th>
                                        <th scope="col">Downloads</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody v-if="!archives.length">
                                    <tr>
                                        <td colspan="100%" v-html="listingMessage"></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr v-for="(archive, aKey) in archives" :key="aKey">
                                        <th scope="row">{{ archive.id }}</th>
                                        <td>{{ moment(archive.created_at).fromNow() }}</td>
                                        <td class="text-truncate" style="max-width: 150px;" :title="archive.original_name">{{ archive.original_name }}</td>
                                        <!-- <td v-if="archive.user_id">{{ archive.user_id }}</td>
                                        <td v-else>unknown</td> -->
                                        <td>{{ formatBytes(archive.size) }}</td>
                                        <td class="d-none d-lg-table-cell">{{ archive.md5_checksum }}</td>
                                        <td>{{ archive.times_downloaded }}</td>
                                        <td>
                                            <a :href="`api/v1/archive/${archive.id}/download`" class="btn btn-link" title="Download">
                                                <i class="fa-solid fa-download"></i>
                                                <span class="d-md-none">
                                                    Download
                                                </span>
                                            </a>
                                            <button type="button" class="btn btn-link" @click="confirmDelete(archive.id)" title="Delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span class="d-md-none">
                                                    Delete
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item" :class="{disabled : previousPage == 0}">
                                    <a class="page-link" href="#" aria-label="Previous" @click.prevent="getArchives(currentPage - 1)">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>

                                <li class="page-item" v-if="pageCount > 1" v-for="i in pageCount"><a class="page-link" :class="{'active' : currentPage == i }" href="#" @click.prevent="getArchives(i)">{{ i }}</a></li>

                                <li class="page-item disabled" v-if="pageCount == 0"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"  :class="{disabled : nextPage == 0}">
                                    <a class="page-link" href="#" aria-label="Next" @click.prevent="getArchives(currentPage + 1)">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <b-modal ref="fileUploadModal" name="fileUploadModal" id="fileUploadModal" hide-footer>
            <template #modal-header="{ close }">
            <h5>File Upload</h5>
            <b-button class="float-right " size="sm" variant="secondary" @click="close()">
                <i class="fa-sharp fa-solid fa-circle-xmark"></i>
            </b-button>
            </template>
            <div class="content">
                <drop-file @file-uploaded="closeAndRefresh()"></drop-file>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import 'bootstrap';

    import moment from 'moment';
    import DropFile from './DropFile.vue';

    export default {
        components: { DropFile },
        data: () => ({
            archives: [],
            listingMessage: "",
            currentPage: 1,
            pageCount: 0,
            nextPage: 0,
            previousPage: 0,
            moment: moment
        }),
        methods: {
            confirmDelete(id) {
                console.log("confirming delete");
                if (window.confirm("Are you sure you want to delete record #" + id + "?")) {
                    axios.delete('api/v1/archive/' + id)
                    .then((res) => {
                        this.$toast.open(
                            {
                                message: "Archive deleted successfully!",
                                duration: 5000,
                                type:  'info'
                            }
                        );
                        this.getArchives();
                    })
                    .catch((err) => {
                        console.log(err);
                        this.$toast.open(
                            {
                                message: "Unable to deleted archive. Please try again",
                                duration: 5000,
                                type:  'error'
                            }
                        );
                    });
                }
            },
            formatBytes(bytes, decimals = 2) {
                // from https://stackoverflow.com/questions/15900485/correct-way-to-convert-size-in-bytes-to-kb-mb-gb-in-javascript
                if (!+bytes) {
                    return '0 Bytes';
                }

                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];

                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
            },
            closeAndRefresh() {
                this.$refs['fileUploadModal'].hide();
                this.getArchives(this.currentPage, true);
            },
            getArchives(pageNumber = 1, force = false) {
                if (!force && pageNumber == this.currentPage) {
                    console.log("already on same page");
                    return;
                }
                axios.get('api/v1/archive?page=' + pageNumber)
                .then((res) => {
                    this.archives = res.data.data;
                    if (this.archives.length == 0) {
                        this.listingMessage = `
<div class="alert alert-info" role="alert">
    <span>
        <i class="fa-solid fa-circle-exclamation"></i>
        No archives found. You can add archives to this listing using the "Upload" button.
    </span>
</div>
`;
                    }

                    // update pagination
                    let pagination = res.data.meta;
                    let pageLink = res.data.links;
                    if (pagination.current_page) {
                        this.currentPage = pagination.current_page;
                    }

                    if (pagination.last_page) {
                        this.pageCount = pagination.last_page;
                    }

                    if (pageLink.prev) {
                        this.previousPage = this.currentPage - 1;
                    } else {
                        this.previousPage = 0;
                    }

                    if (pageLink.next) {
                        this.nextPage = this.currentPage + 1;
                    } else {
                        this.nextPage = 0;
                    }
                })
                .catch((err) => {
                    // alert("Unable to get listing, please try again");
                    console.log(err);
                    this.listingMessage = `
<div class="alert alert-warning" role="alert">
    <span>
        <i class="fa-solid fa-triangle-exclamation"></i>
        Unable to get listing, please try again
    </span>
</div>
`;
                });
            }
        },
        mounted() {
            // this.$toast.open(
            //     {
            //         message: "Toast test!",
            //         duration: 5000,
            //     }
            // );
            this.listingMessage = `
<div class="alert alert-info" role="alert">
    <div class="spinner-border text-info spinner-grow-sm" role="status">
        <span class="sr-only"></span>
    </div>
    Loading listing...
</div>
`;
            // force on first load
            this.getArchives(1, true);
        }
    }
</script>
