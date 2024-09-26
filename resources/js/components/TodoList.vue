<template>
    <div class="container">
      <div class="row justify-center">
        <div class="col-12 md-8">
          <div class="card pa-5">
            <div class="card-title">
              <h1 class="text-center">To-Do Planning</h1>
            </div>

            <!-- Error Message -->
            <div v-if="errorMessage" class="alert alert-danger">
              {{ errorMessage }}
            </div>

            <!-- Loading Indicator -->
            <div v-if="isLoading" class="row justify-center">
              <div class="col-12 text-center">
                <div class="spinner"></div>
              </div>
            </div>

            <!-- Main Content -->
            <div v-else>
              <div class="col-12">
                <h2>Task Listesi</h2>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Başlık</th>
                      <th>Süre (Saat)</th>
                      <th>Zorluk</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="task in tasks" :key="task.title">
                      <td>{{ task.title }}</td>
                      <td>{{ task.time }}</td>
                      <td><span :class="getTaskLevelClass(task.level)">{{ task.level }}</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Developers and Their Tasks -->
              <div class="col-12">
                <h2>Developerlar</h2>
                <div v-for="developer in developers" :key="developer.name" class="accordion">
                  <button class="accordion-header" @click="toggleAccordion($event)">
                    {{ developer.name }}
                  </button>
                  <div class="accordion-content">
                    <ul>
                      <li v-for="task in developer.tasks" :key="task.title">
                        {{ task.title }} ({{ task.time }} hours) - {{ task.level }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Estimated Weeks -->
              <div class="col-12">
                <h3 v-if="estimatedWeeks > 0">Tahmini Haftalar: {{ estimatedWeeks }}</h3>
                <h3 v-else>Tahmini Haftalar bilgisi mevcut değil.</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        tasks: [],
        developers: [],
        estimatedWeeks: 0,
        isLoading: false,
        errorMessage: ''
      };
    },
    methods: {
      fetchTasks() {
        this.isLoading = true;
        this.errorMessage = '';
        axios.get('/api/developers')
          .then(response => {
            this.tasks = response.data.tasks;
            this.developers = response.data.developers;
            this.estimatedWeeks = response.data.weeks;
          })
          .catch(error => {
            console.error('Error fetching tasks:', error);
            this.errorMessage = 'Failed to fetch tasks, please try again later.';
          })
          .finally(() => {
            this.isLoading = false;
          });
      },
      getTaskLevelClass(level) {
        if (level === 1) return 'badge badge-success';
        if (level === 5) return 'badge badge-danger';
        return 'badge badge-warning';
      },
      toggleAccordion(event) {
        const content = event.target.nextElementSibling;
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
      }
    },
    created() {
      this.fetchTasks();
    }
  };
  </script>

  <style scoped>
  .container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
  }

  .text-center {
    text-align: center;
  }

  .card {
    border: 1px solid #ccc;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
  }

  .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
  }

  .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #09f;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  .table {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
  }

  .table th,
  .table td {
    padding: 0.75rem;
    text-align: left;
    border-top: 1px solid #dee2e6;
  }

  .accordion {
    margin-bottom: 10px;
  }

  .accordion-header {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
    outline: none;
  }

  .accordion-content {
    display: none;
    padding: 10px;
    background-color: #f1f1f1;
  }

  .badge {
    padding: 0.5em;
    color: white;
    border-radius: 0.25em;
  }

  .badge-success {
    background-color: green;
  }

  .badge-danger {
    background-color: red;
  }

  .badge-warning {
    background-color: orange;
  }
  </style>
