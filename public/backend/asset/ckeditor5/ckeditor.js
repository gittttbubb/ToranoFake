ClassicEditor
    .create(document.querySelector('.editor_des'), {
        extraPlugins: [MyCustomUploadAdapterPlugin] // Kích hoạt plugin upload ảnh base64
    })
    .catch(error => {
        console.error(error);
    });
ClassicEditor
    .create(document.querySelector('.editor_content'), {
        extraPlugins: [MyCustomUploadAdapterPlugin] // Kích hoạt plugin upload ảnh base64
    })
    .catch(error => {
        console.error(error);
    });

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
}

class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve({ default: reader.result });
                reader.onerror = error => reject(error);
            });
        });
    }

    abort() {
        // Không cần thiết vì upload xảy ra ngay lập tức
    }
}



