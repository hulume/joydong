<template>
<div class="text-center">
<div v-if="type==='button'">
  <div class="uploader uploader-text">
  <input type="file" name="file" id="file" class="inputfile" accept="image/png, image/jpeg, image/gif, image/jpg" @change="onUpload" />
<label for="file"><i class="fa fa-upload uploader-icon"></i><p style="margin-bottom: 10px; color: #97a8be"><slot>点击上传图片</slot></p></label>
  </div>
</div>
    <div v-else class="user-image">
      <div class="thumbnail">
        <img :src="source">
      </div>
      <div class="user-image-buttons">
        <span class="btn btn-primary btn-file"><i class="fa fa-pencil"></i><input type="file"  accept="image/png, image/jpeg, image/gif, image/jpg" @change="onUpload"></span>
        <!-- <span class="btn btn-danger" @click="removeAvatar"><i class="fa fa-times"></i></span> -->
      </div>
    </div>
    </div>
</template>
<script>
  export default {
    data () {
      return {
        newImg: '',
        tempImg: ''
      }
    },
    props: {
      source: String,
      apiUrl: String,
      type: {
        type: String,
        default: 'normal'
      },
      params: {
        type: Object,
        default: () => {
          return {}
        }
      }
    },
    methods: {
      onUpload (e) {
        let files = e.target.files || e.dataTransfer.files
        if (!files.length) {
          return
        }
        this.$store.commit('loading')
        // this.newImg === '' ? this.tempImg = this.source : this.tempImg = this.newImg
        this.newImg = window.URL.createObjectURL(e.target.files[0])
        let formData = new FormData()
        if (this.params.length>0) {
          formData.append(params)
        }
        formData.append('file', files[0])
        axios.post(this.apiUrl, formData)
                  .then((response) => {
                    this.$store.commit('loaded')
                    this.$emit('update:source', response.data.url)
                    this.$emit('update')
                  })
                  .catch((error) => {
                    this.$store.commit('loaded')
                  })
      },
      removeAvatar () {
        this.$emit('delete')
      }
    }
  }
</script>
<style lang="scss" scoped>
.user-image {
  position: relative;
  display: inline-block;
  img{
    max-width: 500px;
    width: 100%;
    border: 0;
    vertical-align: middle;
  }
}
.thumbnail {
    padding: 4px;
    line-height: 1.42857143;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    -webkit-transition: border .2s ease-in-out;
    -o-transition: border .2s ease-in-out;
    transition: border .2s ease-in-out;
    margin-bottom: 20px;
    display: block;
}
.thumbnail a>img, .thumbnail>img {
    margin-right: auto;
    margin-left: auto;
    display: block;
    height: auto;
}
.user-image:hover .user-image-buttons {
    display: block;
}
.user-image .user-image-buttons {
  position: absolute;
  top: 10px;
  right: 10px;
  display: none;
}
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: 0;
    background: #fff;
    cursor: inherit;
    display: block;
}
.inputfile {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
.uploader {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    position: relative;
    overflow: hidden;
    display: inline-block;
    text-align: center;
}
.uploader-icon {
    font-size: 58px;
    color: #97a8be;
    width: 178px;
    height: 138px;
    line-height: 138px;
    text-align: center;
    cursor: pointer;
}
</style>