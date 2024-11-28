import Axios from "axios"

class ApiClient {

    static activeToken() {
        this.access_token = sessionStorage.getItem('access_token');
        this.expires_in = new Date(sessionStorage.getItem('token_expires'));

        if ( !this.access_token || this.expires_in.getTime() < (new Date()).getTime())
            return '';

        return this.access_token;
    }

    static async init(authorize = false) {
        let token = '';        
        if (authorize) {
            token = this.activeToken();  
            if ( !token )
                token = await this.authorize();
        }                    
        
        let result = Axios.create({
            baseURL: window.location.origin.replace(/[#\/]$/, '')+"/api",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': token ? 'Bearer '+token : ''
            }
        })

        result.interceptors.response.use(
            response => response, // Успешные ответы
            error => {
                return Promise.resolve(error.response || { data: 'Unknown error' });
            }
        );

        return result;
    }

    static async authorize() {
        const apiClient = await this.init();                
        let response = await apiClient.post( "/login", {'email':'test@gmail.com', 'password':'userpassword'} );
        
        if ( response.status!=200 || !response.data?.access_token || !response.data?.expires_in) 
            throw new TypeError("authorization error");

        sessionStorage.setItem('access_token', response.data.access_token);
        sessionStorage.setItem('token_expires', (new Date((new Date()).getTime()+response.data.expires_in*1000)).toISOString() ); 
        
        return response.data.access_token;
    }

}

export default ApiClient;