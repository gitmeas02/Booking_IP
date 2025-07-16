// MinIO Private File Service
class MinIOPrivateFileService {
    constructor(baseUrl = 'http://localhost:8100/api', token = null) {
        this.baseUrl = baseUrl;
        this.token = token;
    }

    // Set authentication token
    setToken(token) {
        this.token = token;
    }

    // Get headers with authentication
    getHeaders() {
        const headers = {
            'Accept': 'application/json',
        };
        
        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }
        
        return headers;
    }

    // Upload private file
    async uploadPrivateFile(file, directory = '') {
        const formData = new FormData();
        formData.append('file', file);
        if (directory) {
            formData.append('directory', directory);
        }

        try {
            const response = await fetch(`${this.baseUrl}/files/private/upload`, {
                method: 'POST',
                headers: {
                    ...this.getHeaders(),
                    // Don't set Content-Type for FormData, let browser set it
                },
                body: formData
            });

            const data = await response.json();
            
            if (response.ok) {
                return {
                    success: true,
                    ...data
                };
            } else {
                return {
                    success: false,
                    message: data.message || 'Upload failed'
                };
            }
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }

    // Get temporary URL for private file
    async getPrivateFileUrl(path) {
        try {
            const response = await fetch(`${this.baseUrl}/files/private/${encodeURIComponent(path)}`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            const data = await response.json();
            
            if (response.ok) {
                return {
                    success: true,
                    url: data.url,
                    expiresIn: data.expires_in
                };
            } else {
                return {
                    success: false,
                    message: data.error || 'Failed to get file URL'
                };
            }
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }

    // Stream private file directly
    async streamPrivateFile(path) {
        try {
            const response = await fetch(`${this.baseUrl}/files/private/stream/${encodeURIComponent(path)}`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            if (response.ok) {
                return {
                    success: true,
                    blob: await response.blob(),
                    contentType: response.headers.get('content-type')
                };
            } else {
                return {
                    success: false,
                    message: 'Failed to stream file'
                };
            }
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }

    // Download private file
    async downloadPrivateFile(path, filename = null) {
        try {
            const response = await fetch(`${this.baseUrl}/files/private/download/${encodeURIComponent(path)}`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            if (response.ok) {
                const blob = await response.blob();
                
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename || path.split('/').pop();
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                return {
                    success: true,
                    message: 'File downloaded successfully'
                };
            } else {
                return {
                    success: false,
                    message: 'Failed to download file'
                };
            }
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }

    // Delete private file
    async deletePrivateFile(path) {
        try {
            const response = await fetch(`${this.baseUrl}/files/private/${encodeURIComponent(path)}`, {
                method: 'DELETE',
                headers: this.getHeaders()
            });

            const data = await response.json();
            
            return {
                success: data.success,
                message: data.message
            };
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }

    // List files in private directory
    async listPrivateFiles(directory = '') {
        try {
            const url = directory 
                ? `${this.baseUrl}/files/private/list/${encodeURIComponent(directory)}`
                : `${this.baseUrl}/files/private/list`;
                
            const response = await fetch(url, {
                method: 'GET',
                headers: this.getHeaders()
            });

            const data = await response.json();
            
            if (response.ok) {
                return {
                    success: true,
                    files: data.files
                };
            } else {
                return {
                    success: false,
                    message: data.error || 'Failed to list files'
                };
            }
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }

    // Create object URL for displaying images/videos
    async createObjectUrl(path) {
        const result = await this.streamPrivateFile(path);
        
        if (result.success) {
            return {
                success: true,
                url: URL.createObjectURL(result.blob),
                contentType: result.contentType
            };
        }
        
        return result;
    }
}

export default MinIOPrivateFileService;
