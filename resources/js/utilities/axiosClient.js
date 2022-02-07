import axios from 'axios'

const axiosClient = axios.create({
  baseURL: process.env.API_DOMAIN,
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'Application/json'
  }
})

export default axiosClient