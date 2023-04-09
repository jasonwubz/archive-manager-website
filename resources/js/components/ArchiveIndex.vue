<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Archive Manager</h1>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Archive Listing
                        <button type="button" class="btn btn-primary float-right">Upload</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Lorem</th>
                                        <th scope="col">Ipsum</th>
                                        <th scope="col">Dolor</th>
                                    </tr>
                                </thead>
                                <tbody v-if="!archives.length">
                                    <tr>
                                        <td colspan="4">Sit</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr v-for="(archive, aKey) in archives" :key="aKey">
                                        <th scope="row">1</th>
                                        <td>Sit</td>
                                        <td>Amet</td>
                                        <td>Consectetur</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
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
    </div>
</template>

<script>
    export default {
        data: () => ({
            archives : []
        }),
        methods: {
            getArchives() {
                axios.get('api/v1/archive?page=2')
                .then((res) => {
                    this.archives = res.data.data;
                })
                .catch((err) => {
                    alert("Unable to get listing, please try again");
                    console.log(err);
                });
            }
        },
        mounted() {
            this.getArchives();
        }
    }
</script>
